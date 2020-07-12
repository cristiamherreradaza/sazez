<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductoIdToCuponesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupones', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_id')->nullable()->after('user_id');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cupones', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
        });
    }
}
