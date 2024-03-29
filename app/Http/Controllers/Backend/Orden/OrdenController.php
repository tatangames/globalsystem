<?php

namespace App\Http\Controllers\Backend\Orden;

use App\Http\Controllers\Controller;
use App\Models\Acta;
use App\Models\Administradores;
use App\Models\CatalogoMateriales;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\CuentaProy;
use App\Models\CuentaproyPartidaAdicional;
use App\Models\CuentaProyRestante;
use App\Models\CuentaProyRetenido;
use App\Models\MoviCuentaProy;
use App\Models\ObjEspecifico;
use App\Models\Orden;
use App\Models\Proveedores;
use App\Models\Proyecto;
use App\Models\Requisicion;
use App\Models\RequisicionDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use function Illuminate\Events\queueable;

// Definir zona horaria o region.
date_default_timezone_set('America/El_Salvador');
setlocale(LC_TIME, "spanish");

//Estados de las Ordenes
// 0 - Default
// 1 - Activa
// 2 - Anulada

//Estados de las Cotizaciones
// 0 - Default
// 1 - Aprobada
// 2 - Denegada

class OrdenController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // crear una nueva orden
    public function generarOrden(Request $request){

        $regla = array(
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0];}

        if($info = Orden::where('cotizacion_id', $request->idcoti)->first()){
            // no hacer nada, evita ordenes duplicadas
            return ['success' => 1, 'id' => $info->id];
        }

        if($infocoti = Cotizacion::where('id', $request->idcoti)->first()){
            if($infocoti->estado != 1){
                // por seguridad solo queremos cotizaciones aprobadas
                return ['success' => 2];
            }
        }

        DB::beginTransaction();
        try {

            $infoCoti = Cotizacion::where('id', $request->idcoti)->first();
            $infoRequi = Requisicion::where('id', $infoCoti->requisicion_id)->first();

            $infoProyecto = Proyecto::where('id', $infoRequi->id_proyecto)->first();

            if($infoProyecto->id_estado == 3){
                // pausado

                $texto = "El estado del proyecto es Pausado";
                return ['success' => 3, 'mensaje' => $texto];
            }

            if($infoProyecto->id_estado == 4){
                // finalizado

                $texto = "El estado del proyecto es Finalizado";
                return ['success' => 3, 'mensaje' => $texto];
            }

            // LA ORDEN DE COMPRA NO NECESITA VALIDACIÓN DE SI HAY DINERO, YA QUE CUANDO SE HACE LA
            // COTIZACIÓN, SE RESERVA EL DINERO, Y ESTE NO CAMBIARA EN NINGÚN MOMENTO

            $or = new Orden();
            $or->admin_contrato_id = $request->admin;
            $or->cotizacion_id = $request->idcoti;
            $or->fecha_orden = $request->fecha;
            $or->lugar = $request->lugar;
            $or->estado = 0;
            $or->fecha_anulada = null;
            $or->save();

            $detalle = CotizacionDetalle::where('cotizacion_id', $request->idcoti)->get();

            foreach ($detalle as $dd){

                $infoRequiDeta = RequisicionDetalle::where('id', $dd->id_requidetalle)->first();
                $infoMaterial = CatalogoMateriales::where('id', $infoRequiDeta->material_id)->first();

                $infoCuenta = CuentaProy::where('proyecto_id', $infoRequi->id_proyecto)
                    ->where('objespeci_id', $infoMaterial->id_objespecifico)
                    ->first();

                $cuenta = new CuentaProyRestante();
                $cuenta->id_cuentaproy = $infoCuenta->id;
                $cuenta->id_requi_detalle = $infoRequiDeta->id;
                $cuenta->id_orden = $or->id;
                $cuenta->save();

                // BORRAR MATERIAL RETENIDO, pero si es anulada, volvera a retenido

                CuentaProyRetenido::where('id_cuentaproy', $infoCuenta->id)
                    ->where('id_requi_detalle', $infoRequiDeta->id)
                    ->delete();
            }

            DB::commit();
            return ['success' => 1, 'id' => $or->id];

        }catch(\Throwable $e){
            Log::info('error: ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }

    function redondear_dos_decimal($valor){
        $float_redondeado = round($valor * 100) / 100;
        return $float_redondeado;
    }

    // generar PDF de orden de compra y variable {cantidad} es # de material por hoja
    public function vistaPdfOrden($id, $cantidad){ // id de la orden

        $orden = Orden::where('id', $id)->first();
        $cotizacion = Cotizacion::where('id', $orden->cotizacion_id)->first();
        $requisicion = Requisicion::where('id',  $cotizacion->requisicion_id)->first();
        $proyecto =  Proyecto::where('id',  $requisicion->id_proyecto)->first();
        $proveedor =  Proveedores::where('id',  $cotizacion->proveedor_id)->first();
        $administrador = Administradores::where('id',  $orden->admin_contrato_id)->first();
        $det_cotizacion = CotizacionDetalle::where('cotizacion_id',  $orden->cotizacion_id)->get();

        $total = 0;

        $dataArray = array();
        $array_merged = array();
        $vuelta = 0;

        foreach ($det_cotizacion as $dd){
            $vuelta += 1;

            $infoRequiDetalle = RequisicionDetalle::where('id', $dd->id_requidetalle)->first();
            $infoMaterial = CatalogoMateriales::where('id', $infoRequiDetalle->material_id)->first();
            $infoObjeto = ObjEspecifico::where('id', $infoMaterial->id_objespecifico)->first();

            if(strlen($infoMaterial->nombre) >= 25){
                $subcadena = substr($infoMaterial->nombre, 0, 25);
            }else{
                $subcadena = $infoMaterial->nombre;
            }

            $multi = $dd->cantidad * $dd->precio_u;
            $total = $total + $multi;

            $precio_u = number_format((float)$dd->precio_u, 2, '.', ',');
            $multi = number_format((float)$multi, 2, '.', ',');

            $dataArray[] = [
                'cantidad' => $dd->cantidad,
                'nombre' => $subcadena,
                'cod_presup' => $infoObjeto->codigo,
                'precio_u' => $precio_u,
                'multi' => $multi
            ];

            // CANTIDAD POR HOJA
            if($vuelta == $cantidad){
                $array_merged[] = array_merge($dataArray);
                $dataArray = array();
                $vuelta = 0;
            }
        }

        if(!empty($dataArray)){
            $array_merged[] = array_merge($dataArray);
        }

        $total = number_format((float)$total, 2, '.', ',');

        //$fecha = strftime("%d-%B-%Y", strtotime($orden->fechaorden));
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = array(date("d", strtotime($orden->fecha_orden) ), $meses[date("n", strtotime($orden->fecha_orden) )-1], date("Y", strtotime($orden->fecha_orden) ) );

        $dia = $fecha[0];
        $mes = $fecha[1];
        $anio = $fecha[2];

        $pdf = PDF::loadView('backend.admin.proyectos.reportes.pdfordencompra', compact('orden',
            'cotizacion','proyecto','dia','mes',
            'anio','proveedor','array_merged',
            'administrador', 'total'));
        //$customPaper = array(0,0,470.61,612.36);
        $customPaper = array(0,0,470.61,612.36);
        $pdf->setPaper($customPaper)->setWarnings(false);
        return $pdf->stream('Orden_Compra.pdf');
    }

    // retorna vista con las ordenes de compras
    public function indexOrdenesCompras(){
        return view('backend.admin.proyectos.ordenes.vistaordenescompra');
    }

    // retorna tabla con las ordenes de compras
    public function tablaOrdenesCompras(){

        $lista = Orden::where('estado', 0) // no denegadas
        ->orderBy('fecha_orden')
        ->get();

        foreach($lista as $val){
            $infoCotizacion = Cotizacion::where('id', $val->cotizacion_id)->first();
            $infoRequisicion = Requisicion::where('id', $infoCotizacion->requisicion_id)->first();
            $infoProveedor = Proveedores::where('id', $infoCotizacion->proveedor_id)->first();
            $proyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

            $val->proyecto_cod = $proyecto->codigo;

            if($infoacta = Acta::where('orden_id', $val->id)->first()){
                $val->actaid = $infoacta->id;
            }else{
                $val->actaid = 0;
            }

            $val->requidestino = $infoRequisicion->destino;
            $val->nomproveedor = $infoProveedor->nombre;
        }

        return view('backend.admin.proyectos.ordenes.tablaordenescompra', compact('lista'));
    }

    // anular una orden de compra
    public function anularCompra(Request $request){

        DB::beginTransaction();
        try {

            // SOLO ANULAR SINO TIENE ACTA GENERADA
            if(Acta::where('orden_id', $request->id)->first()){
                return ['success' => 1];
            }

            if($infoOrden = Orden::where('id', $request->id)->first()){

                $infoCotizacion = Cotizacion::where('id', $infoOrden->cotizacion_id)->first();
                $infoRequisicion = Requisicion::where('id', $infoCotizacion->requisicion_id)->first();
                $infoProyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

                if($infoProyecto->id_estado == 3){
                    // pausado

                    $texto = "El estado del proyecto es Pausado";
                    return ['success' => 3, 'mensaje' => $texto];
                }

                if($infoProyecto->id_estado == 4){
                    // finalizado

                    $texto = "El estado del proyecto es Finalizado";
                    return ['success' => 3, 'mensaje' => $texto];
                }

                // pendiente de anulación
                if($infoOrden->estado == 0){

                    Orden::where('id', $request->id)->update([
                        'estado' => 1,
                        'fecha_anulada' => Carbon::now('America/El_Salvador'),
                    ]);

                    $infoCotiDeta = CotizacionDetalle::where('cotizacion_id', $infoCotizacion->id)->get();

                    // para que vuelva el dinero de RESTANTE, SE DEBERA BORRAR LA FILA
                    CuentaProyRestante::where('id_orden', $infoOrden->id)->delete();

                    // setear por cada material cotizado
                    foreach ($infoCotiDeta as $dd){

                        // para que pueda ser cotizado nuevamente
                        RequisicionDetalle::where('id', $dd->id_requidetalle)->update([
                            'estado' => 0,
                        ]);

                        $infoRequiDetalle = RequisicionDetalle::where('id', $dd->id_requidetalle)->first();
                        $infoMaterial = CatalogoMateriales::where('id', $infoRequiDetalle->material_id)->first();

                        $cuentaProy = CuentaProy::where('proyecto_id', $infoRequisicion->id_proyecto)
                            ->where('objespeci_id', $infoMaterial->id_objespecifico)
                            ->first();

                        // VOLVER A COLOCAR DINERO RETENIDO, porque la orden fue anulada, ya que el material
                        // estara listo para ser cotizado de nuevo.
                        if(!CuentaProyRetenido::where('id_requi_detalle', $infoRequiDetalle->id)
                            ->where('id_cuentaproy', $cuentaProy->id)->first()){

                            $retenido = new CuentaProyRetenido();
                            $retenido->id_requi_detalle = $infoRequiDetalle->id;
                            $retenido->id_cuentaproy = $cuentaProy->id;
                            $retenido->save();
                        }
                    }
                }

                DB::commit();
                return ['success' => 2];
            }else{
                return ['success' => 99];
            }

        }catch(\Throwable $e){
            Log::info('error: ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }

    // generar acta de una orden de compra
    function generarActa(Request $request){

        $regla = array(
            'idorden' => 'required',
            'horaacta' => 'required',
            'fechaacta' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        $infoOrden = Orden::where('id', $request->idorden)->first();
        $infoCotizacion = Cotizacion::where('id', $infoOrden->cotizacion_id)->first();
        $infoRequisicion = Requisicion::where('id', $infoCotizacion->requisicion_id)->first();
        $infoProyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

        if($infoProyecto->id_estado == 3){
            // pausado

            $texto = "El estado del proyecto es Pausado";
            return ['success' => 1, 'mensaje' => $texto];
        }

        if($infoProyecto->id_estado == 4){
            // finalizado

            $texto = "El estado del proyecto es Finalizado";
            return ['success' => 1, 'mensaje' => $texto];
        }

        // si ya existe, solo retornar como que fue guardado
        if($info = Acta::where('orden_id', $request->idorden)->first()){
            return ['success' => 2, 'actaid'=> $info->id];
        }else{
            $acta = new Acta();
            $acta->orden_id = $request->idorden;
            $acta->fecha_acta = $request->fechaacta;
            $acta->hora = $request->horaacta;
            $acta->estado = 1; // acta generada

            if($acta->save()) {
                return ['success' => 2, 'actaid'=> $acta->id];
            }else {
                return ['success' => 99];
            }
        }
    }

    // generar PDF de la acta de compra
    public function reporteActaGenerada($id){

        $acta = Acta::where('id',  $id)->first();
        $orden = Orden::where('id',  $acta->orden_id)->first();
        $cotizacion = Cotizacion::where('id', $orden->cotizacion_id)->first();
        $proveedor =  Proveedores::where('id',  $cotizacion->proveedor_id)->first();
        $administrador = Administradores::where('id',  $orden->admin_contrato_id)->first();
        $requisicion = Requisicion::where('id',  $cotizacion->requisicion_id)->first();
        $proyecto = Proyecto::where('id',  $requisicion->id_proyecto)->first();

        $fecha = strftime("%d-%B-%Y",strtotime($acta->fecha_acta));
        $hora = $acta->hora;

        $pdf = PDF::loadView('backend.admin.proyectos.reportes.pdfactaordencompra', compact('acta','proyecto','fecha','proveedor','administrador','hora','orden'));
        $pdf->setPaper('letter', 'portrait')->setWarnings(false);
        return $pdf->stream('acta_orden_compra.pdf');
    }

    // ---------- **** ORDENES DE COMPRA DENEGADAS ****

    // retorna vista con las ordenes de compras
    public function indexOrdenesComprasDenegadas(){
        return view('backend.admin.proyectos.ordenes.denegadas.vistaordenescompradenegada');
    }

    // retorna tabla con las ordenes de compras denegadas
    public function tablaOrdenesComprasDenegadas(){
        $lista = orden::where('estado', 1) // solo denegadas
        ->orderBy('fecha_orden')
        ->get();

        foreach($lista as $val){
            $infoCotizacion = Cotizacion::where('id', $val->cotizacion_id)->first();
            $infoRequisicion = Requisicion::where('id', $infoCotizacion->requisicion_id)->first();
            $infoProveedor = Proveedores::where('id', $infoCotizacion->proveedor_id)->first();
            $proyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

            $val->proyecto_cod = $proyecto->codigo;

            if($infoacta = Acta::where('orden_id', $val->id)->first()){
                $val->actaid = $infoacta->id;
            }else{
                $val->actaid = 0;
            }

            $val->requidestino = $infoRequisicion->destino;
            $val->nomproveedor = $infoProveedor->nombre;
        }

        return view('backend.admin.proyectos.ordenes.denegadas.tablaordenescompradenegadas', compact('lista'));
    }


}
