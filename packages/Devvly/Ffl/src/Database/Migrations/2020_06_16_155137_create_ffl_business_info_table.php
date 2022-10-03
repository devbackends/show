<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflBusinessInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ffl_business_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ffl_id')->unsigned();
            $table->index('ffl_id');
            $table->foreign('ffl_id')->references('id')->on('ffl');
            $table->string('company_name');
            $table->string('contact_name');
            $table->boolean('retail_store_front');
            $table->boolean('importer_exporter');
            $table->boolean('website');
            $table->string('street_address');
            $table->string('city');
            $table->integer('zip_code');
            $table->integer('phone')->unique();
            $table->string('email')->unique();
            $table->string('business_hours');
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
        Schema::dropIfExists('ffl_business_info');
    }
}
