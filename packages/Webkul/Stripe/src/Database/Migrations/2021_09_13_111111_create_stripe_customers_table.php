<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_id')->unique();
            $table->string('payment_method_id');
            $table->string('subscription_id')->nullable();
            $table->string('card_details');
            $table->string('api_key')->nullable();
            $table->string('public_key')->nullable();
            $table->integer('seller_id')->unsigned();
            $table->boolean('is_approved');
            $table->timestamps();

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
        Schema::dropIfExists('stripe_customers');
    }
}
