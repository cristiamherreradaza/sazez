<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechasPublicacionToCuponesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupones', function (Blueprint $table) {
            $table->datetime('inicio_publicacion')->nullable()->after('fecha_final');
            $table->datetime('final_publicacion')->nullable()->after('fecha_final');
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
            $table->dropColumn('final_publicacion');
            $table->dropColumn('inicio_publicacion');
        });
    }
}
