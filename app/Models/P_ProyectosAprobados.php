<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P_ProyectosAprobados extends Model
{
    use HasFactory;
    protected $table = 'p_proyectos_aprobados';
    public $timestamps = false;
}
