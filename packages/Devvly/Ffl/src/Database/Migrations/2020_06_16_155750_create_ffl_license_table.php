<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ffl_license', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ffl_id')->unsigned();
            $table->index('ffl_id');
            $table->foreign('ffl_id')->references('id')->on('ffl');
            $table->string('license_number', 20)->unique();
            $table->string('license_img');
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
        Schema::dropIfExists('ffl_license');
    }
}
