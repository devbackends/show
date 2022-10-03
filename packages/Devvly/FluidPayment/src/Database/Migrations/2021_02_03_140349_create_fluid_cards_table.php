<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFluidCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fluid_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname');
            $table->string('last_four');
            $table->string('expiration_date');
            $table->string('fluid_card_id');
            $table->string('fluid_customer_id');
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
        Schema::dropIfExists('fluid_cards');
    }
}
