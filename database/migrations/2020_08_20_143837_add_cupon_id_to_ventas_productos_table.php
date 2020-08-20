<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCuponIdToVentasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('cupon_id')->nullable()->after('combo_id');
            $table->foreign('cupon_id')->references('id')->on('cupones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_productos', function (Blueprint $table) {
            $table->dropForeign(['cupon_id']);
            $table->dropColumn('cupon_id');
        });
    }
}
