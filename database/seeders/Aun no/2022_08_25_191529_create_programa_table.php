<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable();
            $table->foreignId('files_id')->nullable();

            $table->string('estaciones_id')->nullable();

            $table->string('eslogan');
            $table->string('titulo');
            $table->string('sipnopsis');
            $table->string('categoria');
            $table->string('autor')->nullable();
            $table->string('frecuencia');
            $table->string('logoipo')->nullable();
            $table->string('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programa');
    }
}
