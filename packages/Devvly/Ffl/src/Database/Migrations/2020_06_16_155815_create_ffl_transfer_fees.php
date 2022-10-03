<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflTransferFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ffl_transfer_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ffl_id')->unsigned();
            $table->index('ffl_id');
            $table->foreign('ffl_id')->references('id')->on('ffl');
            $table->float('long_gun');
            $table->string('long_gun_description');
            $table->float('hand_gun');
            $table->string('hand_gun_description');
            $table->float('nics');
            $table->string('nics_description');
            $table->float('other');
            $table->string('other_description');
            $table->string('payment');
            $table->longText('comments');
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
        Schema::dropIfExists('ffl_transfer_fees');
    }
}
