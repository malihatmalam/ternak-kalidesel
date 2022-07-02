<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('diagnosis_id')->nullable();
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis');
            
            $table->string('image')->nullable();

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
        Schema::dropIfExists('diagnosis_images');
    }
}
