<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClearentCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearent_cart', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
            $table->integer('cart_id')->unsigned();
            $table->foreign('card_id')->references('id')->on('clearent_cards')->onDelete('cascade');
            $table->integer('card_id')->unsigned();
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
        Schema::dropIfExists('clearent_cart');
    }
}
