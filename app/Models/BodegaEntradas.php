<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodegaEntradas extends Model
{
    protected $table = 'bodega_entradas';
    public $timestamps = false;
    use HasFactory;
}