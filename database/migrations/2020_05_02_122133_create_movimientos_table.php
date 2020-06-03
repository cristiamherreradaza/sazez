<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('almacene_id');
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->decimal('precio_compra', 15, 2)->default(0);
            $table->decimal('precio_venta', 15, 2)->default(0);
            $table->decimal('ingreso', 15, 2)->default(0);
            $table->decimal('salida', 15, 2)->default(0);
            $table->datetime('fecha')->default(0);
            $table->integer('numero')->default(0);
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
        Schema::dropIfExists('movimientos');
    }
}
