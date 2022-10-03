<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductFlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->boolean('is_listing_fee_charged')->default(0);
            $table->boolean('is_seller_new')->default(0);
            $table->boolean('is_seller_featured')->default(0);
            $table->boolean('is_seller_approved')->default(0);
            $table->string('shipping_type')->default('auto_calculated');
            $table->integer('quantity')->default(0);
            $table->integer('ordered_quantity')->default(0);
            $table->integer('marketplace_seller_id')->unsigned();
            $table->foreign('marketplace_seller_id')->references('id')->on('marketplace_sellers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

