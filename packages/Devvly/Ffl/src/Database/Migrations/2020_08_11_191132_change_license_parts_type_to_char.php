<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLicensePartsTypeToChar extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ffl_license', function (Blueprint $table) {
            $table->string('license_sequence', 5)->change();
            $table->string('license_fips', 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ffl_license', function (Blueprint $table) {
            $table->integer('license_sequence')->change();
            $table->integer('license_fips')->change();
        });
    }
}
