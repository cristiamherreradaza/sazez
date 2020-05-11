<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('marca_id');
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->unsignedBigInteger('tipo_id');
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->string('codigo', 150)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('nombre_venta', 500)->nullable();
            $table->string('tipo', 120)->nullable();
            $table->string('modelo', 120)->nullable();
            $table->decimal('precio_compra', 15, 2)->default(0);
            $table->decimal('cantidad_minima', 15, 2)->default(0);
            $table->decimal('largo', 6, 2)->default(0);
            $table->decimal('ancho', 6, 2)->default(0);
            $table->decimal('alto', 6, 2)->default(0);
            $table->decimal('peso', 6, 2)->default(0);
            $table->string('colores', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('url_referencia')->nullable();
            $table->text('video')->nullable();
            $table->string('estado', 30)->nullable();
            $table->datetime('deleted_at')->nullable();
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
        Schema::dropIfExists('productos');
    }
}
