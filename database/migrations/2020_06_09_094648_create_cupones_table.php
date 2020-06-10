<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuponesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('users');
            $table->unsignedBigInteger('almacene_id')->nullable();
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->integer('descuento')->nullable()->default(1);
            $table->decimal('monto_total', 15, 2)->nullable()->default(0);
            $table->string('codigo', 20)->unique()->nullable();
            $table->string('estado', 30)->nullable()->default('Vigente');
            $table->datetime('fecha_inicio')->nullable();
            $table->datetime('fecha_final')->nullable();
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
        Schema::dropIfExists('cupones');
    }
}
