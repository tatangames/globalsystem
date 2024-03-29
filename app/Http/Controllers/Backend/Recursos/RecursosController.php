<?php

namespace App\Http\Controllers\Backend\Recursos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecursosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // retorna vista de recursos humanos
    public function indexRecursosHumanos(){
        return view('backend.admin.recursos.recursoshumanos.vistarecursoshumanos');
    }
}
