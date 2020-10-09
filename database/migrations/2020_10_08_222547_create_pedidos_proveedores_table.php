<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_proveedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('almacene_id');
            // $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->unsignedBigInteger('proveedore_id');
            // $table->foreign('proveedore_id')->references('id')->on('proveedores');
            $table->integer('numero')->nullable();
            $table->datetime('fecha')->nullable();
            $table->string('estado', 30)->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('pedidos_proveedores', function($table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->foreign('proveedore_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos_proveedores');
    }
}
