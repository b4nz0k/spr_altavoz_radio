<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadFilesTable extends Migration
{

    public function up()
    {
        Schema::create('upload_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('programa_id')->nullable();

            $table->integer('estacion_radio_id')->nullable();
            $table->string('file_name_original');
            $table->string('file_name_renombrado');
            $table->double('file_size');
            $table->string('file_extension');
            $table->string('file_tipo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_files');
    }
}
