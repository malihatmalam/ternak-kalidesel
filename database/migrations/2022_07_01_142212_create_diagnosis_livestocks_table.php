<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisLivestocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_livestocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('diagnosis_id')->nullable();
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis')->onDelete('no action');

            $table->unsignedBigInteger('livestock_id')->nullable();
            $table->foreign('livestock_id')->references('id')->on('livestocks')->onDelete('no action');
            
            $table->string('status'); // sembuh, terjangkit

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
        Schema::dropIfExists('diagnosis_livestocks');
    }
}
