<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTransferFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ffl_transfer_fees', function (Blueprint $table) {
            $table->string('long_gun_description')->nullable()->change();
            $table->string('other')->nullable()->change();
            $table->string('hand_gun_description')->nullable()->change();
            $table->string('nics_description')->nullable()->change();
            $table->string('other_description')->nullable()->change();
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
            $table->string('long_gun_description')->nullable(false)->change();
            $table->string('other')->nullable(false)->change();
            $table->string('hand_gun_description')->nullable(false)->change();
            $table->string('nics_description')->nullable(false)->change();
            $table->string('other_description')->nullable(false)->change();
        });
    }
}
