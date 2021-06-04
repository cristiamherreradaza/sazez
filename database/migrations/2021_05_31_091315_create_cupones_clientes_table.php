<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuponesClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupones_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cupone_id')->nullable();
            $table->foreign('cupone_id')->references('id')->on('cupones');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->foreign('combo_id')->references('id')->on('productos');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('users');
            $table->unsignedBigInteger('almacene_id')->nullable();
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->datetime('fecha_creacion')->nullable();
            $table->datetime('fecha_cobro')->nullable();
            $table->integer('descuento')->nullable()->default(1);
            $table->decimal('monto_total', 15, 2)->nullable()->default(0);
            $table->string('codigo', 20)->unique()->nullable();
            $table->string('nombre', 200)->nullable();
            $table->string('ci', 20)->nullable();
            $table->datetime('fecha_inicio')->nullable();
            $table->datetime('fecha_final')->nullable();
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
        Schema::dropIfExists('cupones_clientes');
    }
}
