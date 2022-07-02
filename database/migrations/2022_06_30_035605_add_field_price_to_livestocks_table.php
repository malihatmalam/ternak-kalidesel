<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPriceToLivestocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('livestocks', function (Blueprint $table) {
            $table->integer('harga')->nullable()->after('berat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('livestocks', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
}
