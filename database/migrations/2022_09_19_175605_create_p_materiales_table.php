<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePMaterialesTable extends Migration
{
    /**
     * PARA PRESUPUESTO DE UNIDAD
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_materiales', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_objespecifico')->unsigned();
            $table->bigInteger('id_unidadmedida')->unsigned();

            $table->string('descripcion', 300);
            $table->decimal('costo', 10, 2);

            // 0: NO VISIBLE
            // 1: VISIBLE
            $table->boolean('visible');

            $table->foreign('id_objespecifico')->references('id')->on('obj_especifico');
            $table->foreign('id_unidadmedida')->references('id')->on('p_unidadmedida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_materiales');
    }
}
