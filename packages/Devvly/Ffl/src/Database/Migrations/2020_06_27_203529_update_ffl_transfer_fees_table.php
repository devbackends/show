<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFflTransferFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ffl_transfer_fees', function (Blueprint $table) {
            $table->string('long_gun')->nullable()->change();
            $table->string('hand_gun')->nullable()->change();
            $table->string('nics')->nullable()->change();
            $table->string('payment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ffl_transfer_fees', function (Blueprint $table) {
            $table->string('long_gun')->nullable(false)->change();
            $table->string('hand_gun')->nullable(false)->change();
            $table->string('nics')->nullable(false)->change();
            $table->string('payment')->nullable(false)->change();
        });
    }
}
