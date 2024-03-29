<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBolsonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolson', function (Blueprint $table) {
            $table->id();

            // año de presupuesto unidades
            $table->bigInteger('id_anio')->unsigned();

            // fuente de recursos
            $table->bigInteger('id_fuenter')->unsigned();


            // nombre de la cuenta bolsón
            $table->string('nombre', 200);

            // fecha creación
            $table->date('fecha');

            // será la suma de objetos específicos, del año de presupuesto de unidad
            $table->decimal('monto_inicial', 10, 2);

            $table->foreign('id_anio')->references('id')->on('p_anio_presupuesto');
            $table->foreign('id_fuenter')->references('id')->on('fuenter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bolson');
    }
}
