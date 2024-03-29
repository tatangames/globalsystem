<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePUnidadmedidaTable extends Migration
{
    /**
     * PARA PRESUPUESTO DE UNIDAD
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_unidadmedida', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_unidadmedida');
    }
}
