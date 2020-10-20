<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEscalaIdToProductosPedidoProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos_pedido_proveedores', function (Blueprint $table) {
            $table->unsignedBigInteger('escala_id')->nullable()->after('producto_id');
            $table->foreign('escala_id')->references('id')->on('escalas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos_pedido_proveedores', function (Blueprint $table) {
            $table->dropForeign(['escala_id']);
            $table->dropColumn('escala_id');
        });
    }
}
