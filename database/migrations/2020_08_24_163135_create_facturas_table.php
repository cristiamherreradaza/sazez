<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('almacene_id');
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('users');
            $table->string('numero_autorizacion', 50)->nullable();
            $table->integer('numero_factura')->nullable();
            $table->string('nit_cliente', 50)->nullable();
            $table->string('fecha_compra', 10)->nullable();
            $table->integer('monto_compra')->nullable();
            $table->string('clave', 50)->nullable();
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
        Schema::dropIfExists('facturas');
    }
}
