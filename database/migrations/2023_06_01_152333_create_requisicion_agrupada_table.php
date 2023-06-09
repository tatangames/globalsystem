<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisicionAgrupadaTable extends Migration
{
    /**
     * AGUPADAS POR UN USUARIO CONSOLIDADOR
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisicion_agrupada', function (Blueprint $table) {
            $table->id();

            // PARA EL ADMINISTRADOR DE CONTRATO
            $table->bigInteger('id_contrato')->unsigned();

            // PARA EL EVALUADOR TECNICO
            $table->bigInteger('id_evaluador')->unsigned();

<<<<<<< HEAD
            //DATOS GENERALES DEL REQ AGRUPADO
            $table->date('fecha');
            $table->string('nombreodestino', 800)->nullable();
            $table->string('justificacion', 800)->nullable();
            $table->string('entrega', 350)->nullable();
            $table->string('plazo', 350)->nullable();
            $table->string('lugar', 350)->nullable();
            $table->string('forma', 350)->nullable();
            $table->string('otros', 350)->nullable();
=======
            // AÑO DEL REQUERIMIENTO QUE VIENE DEL SELECT DEL CONSOLIDADOR AL BUSCAR REQUERIMIENTOS
            $table->bigInteger('id_anio')->unsigned();

            $table->date('fecha');
>>>>>>> 8d1df13691b28a8a9d9086c656cc70aaf6654f96


            // CUANDO UCP LO VA A DENEGAR TODOS COMPLETAMENTE
            // 0 - estado defecto
            // 1- denegado por ucp
            $table->boolean('estado');

            $table->string('nota_cancelado', 800)->nullable();
            $table->string('documento', 100)->nullable();



            $table->foreign('id_contrato')->references('id')->on('administradores');
            $table->foreign('id_evaluador')->references('id')->on('administradores');
            $table->foreign('id_anio')->references('id')->on('p_anio_presupuesto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisicion_agrupada');
    }
}