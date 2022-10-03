<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MarketplaceCustomerWishlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_customer_wishlist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('marketplace_seller_id')->nullable()->default(0)->unsigned();
        });

        Schema::table('marketplace_customer_wishlist', function(Blueprint $table) {
            $table->foreign('customer_id', 'customer_wishlist_index')->references('id')->on('customers');
            $table->foreign('product_id', 'product_wishlist_index')->references('id')->on('products');
            $table->foreign('marketplace_seller_id', 'marketplace_seller_wishlist_index')->references('id')->on('marketplace_sellers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_customer_wishlist');
    }
}
