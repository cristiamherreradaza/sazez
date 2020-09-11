<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlmaceneIdToEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('almacene_id')->nullable()->after('id');
            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->datetime('deleted_at')->nullable()->after('nit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['almacene_id']);
            $table->dropColumn('almacene_id');
            $table->dropColumn('deleted_at');
        });
    }
}
