<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingProductDefaultSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_product_default_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('type_of_event')->nullable();
            $table->string('repetition_type')->nullable();
            $table->json('repetition_sequence')->nullable();
            $table->string('repeat_until_type')->nullable();
            $table->string('repeat_until_value')->nullable();
            $table->string('repeat_until_number')->nullable();
            $table->json('slots')->nullable();
            $table->integer('booking_product_id')->unsigned();
            $table->foreign('booking_product_id')->references('id')->on('booking_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_product_default_slots');
    }
}
