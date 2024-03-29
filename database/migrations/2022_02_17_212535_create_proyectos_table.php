<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosTable extends Migration
{
    /**
     * proyectos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_linea')->nullable()->unsigned();
            $table->bigInteger('id_fuentef')->nullable()->unsigned();
            $table->bigInteger('id_fuenter')->nullable()->unsigned();
            $table->bigInteger('id_areagestion')->nullable()->unsigned();
            $table->bigInteger('id_naturaleza')->nullable()->unsigned();
            $table->bigInteger('id_estado')->nullable()->unsigned();
            $table->bigInteger('id_bolson')->nullable()->unsigned();
            $table->bigInteger('id_formulador')->unsigned();


            $table->string('codigo',100)->unique();
            $table->string('nombre',300);
            $table->string('ubicacion',300);
            $table->string('contraparte',300)->nullable();
            $table->date('fechaini')->nullable();
            $table->date('fechafin')->nullable();
            $table->date('fecha');
            $table->string('ejecutor',300)->nullable();
            $table->string('supervisor',300)->nullable();
            $table->string('encargado',300)->nullable();
            $table->string('codcontable', 150)->nullable();
            $table->string('acuerdoapertura', 100)->nullable(); // file
            $table->string('acuerdocierre', 100)->nullable(); // file

            $table->decimal('monto', 10, 2); // cuando presu es aprobado, esto es el monto de partida

            // cuando se finaliza un proyecto se calcula los montos restantes
            $table->decimal('monto_finalizado', 10, 2);


            // imprevisto que toma al aprobar el presupuesto
            $table->decimal('imprevisto_fijo', 10, 2);

            // porcentaje de herramienta
            $table->decimal('porcentaje_herra_fijo', 10, 2);

            // para aprobar las partidas presupuesto
            // 0: default
            // 1: listo para revisión
            // 2: aprobado
            $table->integer('presu_aprobado');
            $table->dateTime('fecha_aprobado')->nullable();

            // utilizado para que jefe presupuesto de permiso de 1 movimiento de cuenta
            // 0: no permiso
            // 1: permiso
            $table->boolean('permiso');

            // utilizado para habilitar botón para agregar partidas adicionales
            // esto modifica jefatura presupuesto
            $table->boolean('permiso_partida_adic');

            // porcentaje de obra adicional, por defecto 20% al crear proyecto
            // esto no debe superar al aprobar cada partida adicional
            $table->decimal('porcentaje_obra', 10, 2);

            $table->foreign('id_linea')->references('id')->on('linea');
            $table->foreign('id_fuentef')->references('id')->on('fuentef');
            $table->foreign('id_fuenter')->references('id')->on('fuenter');
            $table->foreign('id_areagestion')->references('id')->on('areagestion');
            $table->foreign('id_naturaleza')->references('id')->on('naturaleza');
            $table->foreign('id_estado')->references('id')->on('estado_proyecto');
            $table->foreign('id_bolson')->references('id')->on('bolson');
            $table->foreign('id_formulador')->references('id')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
}
