<?php

namespace App\Http\Controllers\Backend\Configuraciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adescos;
use Illuminate\Support\Facades\Validator;

class AdescosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        return view('Backend.Admin.Configuraciones.adescos.vistaAdescos');
    }

    public function tabla(){
        $lista = Adescos::orderBy('nombre', 'ASC')->get();

        return view('Backend.Admin.Configuraciones.adescos.tablaAdescos', compact('lista'));
    }

    public function nuevoAdesco(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $dato = new Adescos();
        $dato->nombre = $request->nombre;
        $dato->presidente = $request->presidente;
        $dato->tel = $request->tel;
        $dato->dui = $request->dui;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    // informacion
    public function informacionAdesco(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Adescos::where('id', $request->id)->first()){

            return ['success' => 1, 'lista' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    // editar
    public function editarAdesco(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Adescos::where('id', $request->id)->first()){

            Adescos::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'presidente' => $request->presidente,
                'tel' => $request->tel,
                'dui' => $request->dui
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


}
