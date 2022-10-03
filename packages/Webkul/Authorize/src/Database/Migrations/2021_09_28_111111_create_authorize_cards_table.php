<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorize_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname');
            $table->string('last_four');
            $table->string('expiration_date');
            $table->string('token',500);
            $table->json('misc')->nullable();
            $table->string('authorize_card_id')->nullable();
            $table->string('authorize_customer_id')->nullable();
            $table->string('authorize_token',500)->nullable();
            $table->integer('is_default')->default(0);
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
        Schema::dropIfExists('authorize_cards');
    }
}
