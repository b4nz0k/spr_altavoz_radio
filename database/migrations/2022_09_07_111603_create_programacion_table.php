<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programacion', function (Blueprint $table) {
            $table->id();
            // Id de la estacion
            $table->foreignId('estaciones_radio_id');
            $table->foreignId('programas_id');
            $table->foreignId('user_id');
            $table->integer('dia');
            $table->string('color')->nullable();
            $table->string('tipo_transmision')->nullable();
            $table->timestamp('hora_inicio');
            $table->timestamp('hora_final');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programacion');
    }
}
