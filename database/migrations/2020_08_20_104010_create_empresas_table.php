<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 255)->nullable();
            $table->string('direccion', 800)->nullable();
            $table->string('actividad', 500)->nullable();
            $table->string('leyenda_consumidor', 800)->nullable();
            $table->string('telefono', 120)->nullable();
            $table->string('fax', 120)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('telefono_fijo', 120)->nullable();
            $table->string('nit', 80)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
