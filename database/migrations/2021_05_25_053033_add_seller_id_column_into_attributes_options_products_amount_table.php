<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellerIdColumnIntoAttributesOptionsProductsAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes_options_products_amount', function (Blueprint $table) {
            $table->integer('seller_id')->unsigned();
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
        Schema::table('attributes_options_products_amount', function (Blueprint $table) {
            $table->dropColumn('seller_id');
        });
    }
}
