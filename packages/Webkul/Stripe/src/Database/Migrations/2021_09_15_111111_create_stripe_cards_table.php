<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname');
            $table->string('last_four');
            $table->string('expiration_date');
            $table->string('token');
            $table->json('misc')->nullable();
            $table->string('stripe_card_id');
            $table->string('stripe_customer_id');
            $table->integer('customer_id')->unsigned();
            $table->integer('seller_id')->unsigned();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('marketplace_sellers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_cards');
    }
}
