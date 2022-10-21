<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdenToBanner extends Migration
{

    public function up()
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->integer('orden')->nullable();
        });
    }


    public function down()
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
}
