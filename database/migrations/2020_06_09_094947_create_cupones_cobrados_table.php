<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuponesCobradosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupones_cobrados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cupone_id')->nullable();
            $table->foreign('cupone_id')->references('id')->on('cupones');
            $table->unsignedBigInteger('cobrador_id')->nullable();
            $table->foreign('cobrador_id')->references('id')->on('users');
            $table->unsignedBigInteger('almacene_id')->nullable();
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->datetime('fecha')->nullable();
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
        Schema::dropIfExists('cupones_cobrados');
    }
}
