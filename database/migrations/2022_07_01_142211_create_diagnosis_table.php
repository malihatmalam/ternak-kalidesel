<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('code')->unique();

            $table->text('complaint')->nullable(); // keluhan yang dirasakan hewan

            $table->text('diagnosis')->nullable();

            // $table->string('disease_indication')->nullable();

            $table->text('treatment')->nullable(); 
            
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
        Schema::dropIfExists('diagnosis');
    }
}
