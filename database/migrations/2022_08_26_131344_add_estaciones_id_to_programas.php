<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstacionesIdToProgramas extends Migration
{

    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('estaciones_ids')->nullable();
        });
    }

    public function down()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn('estaciones_ids');
        });
    }
}
