<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlmacenOrigenIdToMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('almacen_origen_id')->nullable()->after('producto_id');
            $table->foreign('almacen_origen_id')->references('id')->on('almacenes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['almacen_origen_id']);
            $table->dropColumn('almacen_origen_id');
        });
    }
}
