<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingProductEventTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_product_event_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('maximum_event_size')->nullable();
            $table->integer('maximum_ticket_per_booking')->nullable();
            $table->json('tickets')->nullable();
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
        Schema::dropIfExists('booking_product_event_tickets');
    }
}
