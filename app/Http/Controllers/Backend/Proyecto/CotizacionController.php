<?php

namespace App\Http\Controllers\Backend\Proyecto;

use App\Http\Controllers\Controller;
use App\Models\Administradores;
use App\Models\CatalogoMateriales;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\CuentaProy;
use App\Models\MoviCuentaProy;
use App\Models\ObjEspecifico;
use App\Models\Orden;
use App\Models\Presupuesto;
use App\Models\Proveedores;
use App\Models\Proyecto;
use App\Models\Requisicion;
use App\Models\RequisicionDetalle;
use App\Models\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class CotizacionController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function indexPendiente(){
        return view('backend.admin.proyectos.cotizaciones.pendiente.vistacotizacionpendienteing');
    }

    public function indexPendienteTabla(){

        $lista = Cotizacion::where('estado', 0)->orderBy('id', 'ASC')->get();

        foreach ($lista as $dd){

            $dd->fecha = date("d-m-Y", strtotime($dd->fecha));

            $infoProveedor = Proveedores::where('id', $dd->proveedor_id)->first();
            $infoRequisicion = Requisicion::where('id', $dd->requisicion_id)->first();
            $infoProyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

            $dd->proveedor = $infoProveedor->nombre;
            $dd->necesidad = $infoRequisicion->necesidad;
            $dd->destino = $infoRequisicion->destino;
            $dd->codigoproyecto = $infoProyecto->codigo;
        }

        return view('backend.admin.proyectos.cotizaciones.pendiente.tablacotizacionpendienteing', compact('lista'));
    }

    public function indexCotizacion($id){ // id de cotizacion

        // destino, necesidad, proveedor, fecha cotizacion
        $cotizacion = Cotizacion::where('id', $id)->first();
        $info = Requisicion::where('id', $cotizacion->requisicion_id)->first();
        $proveedor = Proveedores::where('id', $cotizacion->proveedor_id)->first();

        $detalle = DB::table('cotizacion_detalle AS cd')
            ->join('materiales AS m', 'cd.material_id', '=', 'm.id')
            ->join('obj_especifico AS obj', 'm.id_objespecifico', '=', 'obj.id')
            ->select('cd.id', 'm.nombre', 'cd.cantidad', 'cd.precio_u', 'obj.nombre AS objnombre',
                        'obj.codigo')
            ->orderBy('obj.codigo', 'ASC')
            ->get();

        $conteo = 0;

        $fecha = date("d-m-Y", strtotime($cotizacion->fecha));

        $totalCantidad = 0;
        $totalPrecio = 0;
        $totalTotal = 0;

        foreach ($detalle as $de){

            $conteo += 1;
            $de->conteo = $conteo;

            $multi = $de->cantidad * $de->precio_u;

            $totalCantidad = $totalCantidad + $de->cantidad;
            $totalPrecio = $totalPrecio + $de->precio_u;
            $totalTotal = $totalTotal + $multi;

            $de->precio_u = number_format((float)$de->precio_u, 2, '.', ',');
            $de->total = number_format((float)$multi, 2, '.', ',');

            $de->objeto = $de->codigo . " - " . $de->objnombre;
        }

        $totalCantidad = number_format((float)$totalCantidad, 2, '.', ',');
        $totalPrecio = number_format((float)$totalPrecio, 2, '.', ',');
        $totalTotal = number_format((float)$totalTotal, 2, '.', ',');

        return view('backend.admin.proyectos.cotizaciones.individual.vistacotizacionindividualing', compact('id', 'info',
            'proveedor', 'detalle', 'fecha', 'totalCantidad', 'totalPrecio', 'totalTotal', 'cotizacion'));
    }

    public function autorizarCotizacion(Request $request){


        DB::beginTransaction();

        try {

            if(Cotizacion::where('id', $request->id)
            ->where('estado', 0)->first()){
                Cotizacion::where('id', $request->id)->update([
                    'estado' => 1,
                    'fecha_estado' => Carbon::now('America/El_Salvador')
                ]);
            }

            DB::commit();
            return ['success' => 1];
        }catch(\Throwable $e){
            DB::rollback();
            return ['success' => 99];
        }
    }

    public function denegarCotizacion(Request $request){

        DB::beginTransaction();

        try {

            // COTIZACION DENEGADA

            Cotizacion::where('id', $request->id)->update([
                'estado' => 2,
                'fecha_estado' => Carbon::now('America/El_Salvador')
            ]);

           // $infoCotizacion = Cotizacion::where('id', $request->id)->first();

            // VOLVER A CAMBIAR DE ESTADO LOS MATERIALES QUE FUERON COTIZADOS

            /*$infoRequi = Requisicion::where('id', $infoCotizacion->requisicion_id)->first();

            // si tenia 1, es que todos los materiales estaban cotizados. volver a 0
            if($infoRequi->estado == 1){
                Requisicion::where('id', $infoRequi->id)->update([
                    'estado' => 0,
                ]);
            }*/

            // hoy verificar cuales otros materiales fueron cotizados y volver a 0

            $listado = CotizacionDetalle::where('cotizacion_id', $request->id)->get();

            foreach ($listado as $ll){
                RequisicionDetalle::where('id', $ll->id_requidetalle)->update([
                    'estado' => 0,
                ]);
            }

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('ee' . $e);
            DB::rollback();
            return ['success' => 99];
        }

    }

    public function indexAutorizadas(){
        $contrato = Administradores::orderBy('nombre')->get();

        return view('backend.admin.proyectos.cotizaciones.procesada.vistacotizacionprocesadaing', compact('contrato'));
    }

    public function indexAutorizadasTabla(){

        // autorizadas
        $lista = Cotizacion::where('estado', 1)
           // ->whereNotIn('id', $pila) // no quiero las que ya se genero la orden de compra
            ->orderBy('fecha', 'DESC') // la ultima cotizacion quiero primero
            ->get();

        foreach ($lista as $dd){

            $dd->fecha = date("d-m-Y", strtotime($dd->fecha));

            $infoProveedor = Proveedores::where('id', $dd->proveedor_id)->first();
            $infoRequisicion = Requisicion::where('id', $dd->requisicion_id)->first();
            $infoProyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

            $dd->proveedor = $infoProveedor->nombre;
            $dd->necesidad = $infoRequisicion->necesidad;
            $dd->destino = $infoRequisicion->destino;
            $dd->codigoproyecto = $infoProyecto->codigo;

            if(Orden::where('cotizacion_id', $dd->id)->first()){
                $dd->bloqueo = true;
            }else{
                $dd->bloqueo = false;
            }
        }

        return view('backend.admin.proyectos.cotizaciones.procesada.tablacotizacionprocesadaing', compact('lista'));
    }

    public function indexDenegadas(){

        return view('backend.admin.proyectos.cotizaciones.denegadas.vistacotizaciondenegadaing');
    }

    public function indexDenegadasTabla(){

        // denegadas
        $lista = Cotizacion::where('estado', 2)->orderBy('id', 'ASC')->get();

        foreach ($lista as $dd){

            $infoProveedor = Proveedores::where('id', $dd->proveedor_id)->first();
            $infoRequisicion = Requisicion::where('id', $dd->requisicion_id)->first();
            $infoProyecto = Proyecto::where('id', $infoRequisicion->id_proyecto)->first();

            $dd->proveedor = $infoProveedor->nombre;
            $dd->necesidad = $infoRequisicion->necesidad;
            $dd->destino = $infoRequisicion->destino;
            $dd->codigoproyecto = $infoProyecto->codigo;

            $dd->fecha = date("d-m-Y", strtotime($dd->fecha));
        }

        return view('backend.admin.proyectos.cotizaciones.denegadas.tablacotizaciondenegadaing', compact('lista'));
    }

    // vista listar requerimientos para que sea visible para UACI
    public function indexListarRequerimientos(){
        return view('backend.admin.proyectos.requerimientos.vistarequerimientosing');
    }

    public function indexTablaListarRequerimientos(){

        $data = DB::table('requisicion AS r')
            ->join('requisicion_detalle AS d', 'd.requisicion_id', '=', 'r.id')
            ->select('r.id_proyecto')
            ->where('d.estado', 0)
            ->where('d.cancelado', 0)
            ->groupBy('r.id_proyecto')
            ->get();

        $pila = array();

        foreach ($data as $dd){
            array_push($pila, $dd->id_proyecto);
        }

        $lista = Proyecto::whereIn('id', $pila)
            ->orderBy('fecha', 'DESC')
            ->get();

        foreach ($lista as $ll){
            $ll->fecha = date("d-m-Y", strtotime($ll->fecha));
        }

        return view('backend.admin.proyectos.requerimientos.tablarequerimientosing', compact('lista'));
    }

    // mostrar la vista de requerimientos pendiente para x proyecto
    public function listadoRequerimientoPorProyecto($id){

        $proveedores = Proveedores::orderBy('nombre')->get();

        return view('backend.admin.proyectos.requerimientos.vistaindividualrequerimientoing', compact('id', 'proveedores'));
    }

    public function tablaRequerimientosIndividual($id){

        $data = DB::table('requisicion AS r')
            ->join('requisicion_detalle AS d', 'd.requisicion_id', '=', 'r.id')
            ->select('r.id')
            ->where('d.estado', 0)
            ->where('d.cancelado', 0)
            ->groupBy('r.id')
            ->get();

        $pila = array(); // array id de requerimientos

        foreach ($data as $dd){
            array_push($pila, $dd->id);
        }

        $lista = Requisicion::whereIn('id', $pila)->orderBy('fecha', 'ASC')->get();

        foreach ($lista as $dd){
            $dd->fecha = date("d-m-Y", strtotime($dd->fecha));
        }

        return view('backend.admin.proyectos.requerimientos.tablaindividualrequerimientoing', compact('lista'));
    }

    public function informacionRequerimiento(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($info = Requisicion::where('id', $request->id)->first()){

            $listado = RequisicionDetalle::where('requisicion_id', $request->id)
                ->where('estado', 0)
                ->where('cancelado', 0)
                ->get();

            foreach ($listado as $l){
                $data = CatalogoMateriales::where('id', $l->material_id)->first();
                $data2 = UnidadMedida::where('id', $data->id_unidadmedida)->first();

                $l->nombre = $data->nombre;
                $l->medida = $data2->medida;
            }

            return ['success' => 1, 'info' => $info, 'listado' => $listado];
        }else{
            return ['success' => 2];
        }
    }

    // obtener listado de materiales de requisición, para mostrar detalle a UACI
    public function verificarRequerimiento(Request $request){

        // La lista de ID que llega son de requisicion_detalle

        // VERIFICAR QUE EXISTAN TODOS LOS MATERIALES A COTIZAR EN REQUISICIÓN DETALLE
        for ($i = 0; $i < count($request->lista); $i++) {

            // SI NO LA ENCUENTRA, EL ADMINISTRADOR BORRO EL MATERIAL A COTIZAR
            if(!RequisicionDetalle::where('id', $request->lista[$i])->first()){
                return ['success' => 1];
            }
        }

        $info = RequisicionDetalle::whereIn('id', $request->lista)
            ->orderBy('id', 'ASC')
            ->get();

        $totalCantidad = 0;
        $totalPrecio = 0;
        $totalMulti = 0;

        foreach ($info as $dd){
            $infoCatalogo = CatalogoMateriales::where('id', $dd->material_id)->first();
            $infoUnidad = UnidadMedida::where('id', $infoCatalogo->id_unidadmedida)->first();
            $infoCodigo = ObjEspecifico::where('id', $infoCatalogo->id_objespecifico)->first();

            $dd->nombre = $infoCatalogo->nombre;
            $dd->pu = $infoCatalogo->pu;
            $dd->medida = $infoUnidad->medida;
            $dd->codigo = $infoCodigo->codigo . " - " . $infoCodigo->nombre;

            $multi = $dd->cantidad * $infoCatalogo->pu;
            $totalMulti = $totalMulti + $multi;

            $dd->multiTotal = number_format((float)$multi, 2, '.', ',');

            $totalCantidad = $totalCantidad + $dd->cantidad;
            $totalPrecio = $totalPrecio + $infoCatalogo->pu;
        }

        $totalCantidad = number_format((float)$totalCantidad, 2, '.', ',');
        $totalPrecio = number_format((float)$totalPrecio, 2, '.', ',');
        $totalMulti = number_format((float)$totalMulti, 2, '.', ',');

        return ['success' => 2, 'lista' => $info,
            'totalCantidad' => $totalCantidad,
            'totalPrecio' => $totalPrecio,
            'totalMulti' => $totalMulti];
    }


    public function guardarNuevaCotizacion(Request $request){

        DB::beginTransaction();

        try {

            // VERIFICAR QUE EXISTAN TODOS LOS MATERIALES A COTIZAR EN REQUISICIÓN DETALLE
            for ($i = 0; $i < count($request->lista); $i++) {

                // SI NO LA ENCUENTRA, EL ADMINISTRADOR BORRO EL MATERIAL A COTIZAR
                if(!RequisicionDetalle::where('id', $request->lista[$i])->first()){
                    return ['success' => 1];
                }
            }

            // crear cotizacion
            $coti = new Cotizacion();
            $coti->proveedor_id = $request->proveedor;
            $coti->requisicion_id = $request->idcotizar;
            $coti->fecha = $request->fecha;
            $coti->fecha_estado = null;
            $coti->estado = 0;
            $coti->save();

            $infoRequisicion = Requisicion::where('id', $request->idcotizar)->first();

            $lista = RequisicionDetalle::whereIn('id', $request->lista)
                ->orderBy('id', 'ASC')
                ->get();

            foreach ($lista as $datainfo){

                // MATERIAL A COTIZACION FUE CANCELADO
                if($datainfo->cancelado == 1){
                    return ['success' => 4];
                }

                if(CotizacionDetalle::where('id_requidetalle', $datainfo->id)->first()){
                    // ENCONTRO MATERIAL COTIZADO, hoy se verificara si este material ya estaba aprobado o tiene una
                    // cotización esperando (aprobación o denegación)
                    $todos = CotizacionDetalle::where('id_requidetalle', $datainfo->id)->get();

                    $pilaCoti = array();

                    foreach ($todos as $dd){
                        array_push($pilaCoti, $dd->cotizacion_id);
                    }

                    $conteoCoti = Cotizacion::whereIn('id', $pilaCoti)
                        ->whereIn('estado', [0,1]) // estado default y aprobados
                        ->count();

                    if($conteoCoti > 0){
                        // SIGNIFICA QUE MATERIAL A COTIZAR YA TENIA UNA COTIZACION EN ESPERA O APROBADA
                        return ['success' => 2];
                    }
                }

                // VERIFICACIÓN DE SALDOS

                $infoCatalogo = CatalogoMateriales::where('id', $datainfo->material_id)->first();

                // ACTUALIZAR PRECIO DEL MATERIAL PRIMERAMENTE
                RequisicionDetalle::where('id', $datainfo->id)->update([
                    'dinero' => $infoCatalogo->pu
                ]);

                $infoObjeto = ObjEspecifico::where('id', $infoCatalogo->id_objespecifico)->first();
                $infoUnidad = UnidadMedida::where('id', $infoCatalogo->id_unidadmedida)->first();

                // como siempre busco material que estaban en el presupuesto, siempre encontrara
                // el proyecto ID y el ID de objeto específico
                $infoPresupuesto = CuentaProy::where('proyecto_id', $infoRequisicion->id_proyecto)
                    ->where('objespeci_id', $infoCatalogo->id_objespecifico)
                    ->first();

                $totalSalida = 0;
                $totalEntrada = 0;
                $totalRetenido = 0;

                $infoSalidaDetalle = DB::table('cuentaproy_detalle AS pd')
                    ->join('requisicion_detalle AS rd', 'pd.id_requi_detalle', '=', 'rd.id')
                    ->select('rd.cantidad', 'rd.dinero', 'rd.cancelado')
                    ->where('pd.id_cuentaproy', $infoPresupuesto->id)
                    ->where('pd.tipo', 0) // salidas
                    ->where('rd.cancelado', 0)
                    ->get();

                foreach ($infoSalidaDetalle as $dd){
                    $totalSalida = $totalSalida + ($dd->cantidad * $dd->dinero);
                }

                $infoEntradaDetalle = DB::table('cuentaproy_detalle AS pd')
                    ->join('requisicion_detalle AS rd', 'pd.id_requi_detalle', '=', 'rd.id')
                    ->select('rd.cantidad', 'rd.dinero', 'rd.cancelado')
                    ->where('pd.id_cuentaproy', $infoPresupuesto->id)
                    ->where('pd.tipo', 1) // entradas
                    ->where('rd.cancelado', 0)
                    ->get();

                foreach ($infoEntradaDetalle as $dd){
                    $totalEntrada = $totalEntrada + ($dd->cantidad * $dd->dinero);
                }

                // obtener cuanto saldo retenido tengo para el objeto específico
                // y el dinero lo obtiene de LA REQUISICIÓN DETALLE

                // OBTENER SALDO RESTANTE POR LOS MOVIMIENTO DE CUENTAS

                $moviSumaSaldo = MoviCuentaProy::where('id_cuentaproy', $infoPresupuesto->id)
                    ->sum('aumento');

                $moviRestaSaldo = MoviCuentaProy::where('id_cuentaproy', $infoPresupuesto->id)
                    ->sum('disminuye');

                $saldoRestante = $moviSumaSaldo - $moviRestaSaldo;

                // esto es lo que hay de SALDO RESTANTE PARA EL OBJETO ESPECÍFICO
                $saldoRestante += $infoPresupuesto->saldo_inicial - ($totalSalida - $totalEntrada);

                // verificar cantidad * dinero del material nuevo
                $saldoMaterial = $datainfo->cantidad * $infoCatalogo->pu;

                // ************* NO SE SUMA EL SALDO RETENIDO. SOLO SE VERIFICA QUE HAYA SALDO RESTANTE.
                $sumaSaldos = $saldoMaterial;

                // verificar si alcanza el saldo para guardar la cotización
                if($this->redondear_dos_decimal($saldoRestante) < $this->redondear_dos_decimal($sumaSaldos)){
                    // retornar que no alcanza el saldo

                    // SALDO RESTANTE Y SALDO RETENIDO FORMATEADOS
                    $saldoRestanteFormat = number_format((float)$saldoRestante, 2, '.', ',');
                    $saldoRetenidoFormat = number_format((float)$totalRetenido, 2, '.', ',');

                    $costoFormat = number_format((float)$infoCatalogo->pu, 2, '.', ',');

                    // disponible - retenido
                    // PASAR A NUMERO POSITIVO
                    $totalActualFormat = abs($saldoRestante - $totalRetenido);
                    $totalActualFormat = number_format((float)$totalActualFormat, 2, '.', ',');

                    return ['success' => 3, 'fila' => $i,
                        'obj' => $infoObjeto->codigo,
                        'disponibleFormat' => $saldoRestanteFormat, // esto va formateado
                        'retenidoFormat' => $saldoRetenidoFormat, // esto va formateado
                        'material' => $infoCatalogo,
                        'unidad' => $infoUnidad->medida,
                        'costo' => $costoFormat,
                        'totalactual' => $totalActualFormat
                    ];
                }else {

                    $detalle = new CotizacionDetalle();
                    $detalle->cotizacion_id = $coti->id;
                    $detalle->id_requidetalle = $datainfo->id;
                    $detalle->material_id = $datainfo->material_id;
                    $detalle->nombre = $infoCatalogo->nombre;
                    $detalle->medida = $infoUnidad->medida;
                    $detalle->cantidad = $datainfo->cantidad;
                    $detalle->precio_u = $infoCatalogo->pu;
                    $detalle->estado = 0;
                    $detalle->save();

                    // cambiar estado de requisiciones detalle porque ya fueron cotizadas
                    RequisicionDetalle::where('id', $datainfo->id)->update([
                        'estado' => 1,
                    ]);
                }
            } // end foreach


            // CAMBIAR DE ESTADO LA REQUISICIÓN COMPLETADO, SI YA NO HAY NADA QUE COTIZAR

            /*$conteo = RequisicionDetalle::where('requisicion_id', $request->idcotizar)
                ->where('estado', 0)
                ->count();

            if($conteo == 0){

                // SE COMPLETO LA COTIZACION DE REQUISICION
                Requisicion::where('id', $request->idcotizar)->update([
                    'estado' => 1,
                ]);
            }*/

            DB::commit();
            return ['success' => 5];
        }catch(\Throwable $e){
            Log::info('ee' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }


    function redondear_dos_decimal($valor) {
        $float_redondeado=round($valor * 100) / 100;
        return $float_redondeado;
    }

    public function vistaDetalleCotizacion($id){
        // id de cotizacion

        // destino, necesidad, proveedor, fecha cotizacion
        $cotizacion = Cotizacion::where('id', $id)->first();
        $info = Requisicion::where('id', $cotizacion->requisicion_id)->first();
        $proveedor = Proveedores::where('id', $cotizacion->proveedor_id)->first();

        $detalle = CotizacionDetalle::where('cotizacion_id', $id)->orderBy('cod_presup', 'ASC')->get();
        $conteo = 0;

        $fecha = date("d-m-Y", strtotime($cotizacion->fecha));

        $totalCantidad = 0;
        $totalPrecio = 0;
        $totalTotal = 0;

        foreach ($detalle as $de){

            $conteo += 1;
            $de->conteo = $conteo;

            $infoDescripcion = CatalogoMateriales::where('id', $de->material_id)->first();
            $de->descripcion = $infoDescripcion->nombre;
            $multi = $de->cantidad * $de->precio_u;

            $totalCantidad = $totalCantidad + $de->cantidad;
            $totalPrecio = $totalPrecio + $de->precio_u;
            $totalTotal = $totalTotal + $multi;

            $de->precio_u = number_format((float)$de->precio_u, 2, '.', ',');
            $de->total = number_format((float)$multi, 2, '.', ',');
        }

        $de->total = number_format((float)$multi, 2, '.', ',');

        $totalCantidad = number_format((float)$totalCantidad, 2, '.', ',');
        $totalPrecio = number_format((float)$totalPrecio, 2, '.', ',');
        $totalTotal = number_format((float)$totalTotal, 2, '.', ',');

        return view('backend.admin.proyectos.cotizaciones.individual.vistacotizaciondetalleing', compact('id', 'info',
            'proveedor', 'detalle', 'fecha', 'totalCantidad', 'totalPrecio', 'totalTotal'));
    }


}
