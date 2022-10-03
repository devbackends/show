<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('qty')->default(0);
            $table->string('type');
            $table->string('instructions')->nullable();
            $table->json('leaders')->nullable();
            $table->string('booking_confirmation_message')->nullable();
            $table->string('tags')->nullable();
            $table->integer('min_age')->default(0);
            $table->integer('max_age')->default(0);
            $table->boolean('age_restrictions')->default(0);
            $table->string('gender')->nullable();
            $table->json('levels')->nullable();
            $table->string('location_type')->nullable();
            $table->string('location')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('location_additional_information')->nullable();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('booking_products');
    }
}
