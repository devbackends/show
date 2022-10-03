<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceFedexShippingCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_fedex_shipping_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id')->nullable();
            $table->string('meter_number')->nullable();
            $table->string('key')->nullable();
            $table->string('password')->nullable();
            $table->integer('marketplace_seller_id')->unsigned();

            $table->foreign('marketplace_seller_id', 'mp_fedex_seller_id')->references('id')->on('marketplace_sellers')->onDelete('cascade');

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
        Schema::dropIfExists('marketplace_fedex_shipping_credentials');
    }
}
