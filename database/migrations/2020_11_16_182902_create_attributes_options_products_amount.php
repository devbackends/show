<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesOptionsProductsAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes_options_products_amount', function (Blueprint $table) {
            $table->integer('category_id')->unsigned();
            $table->integer('option_id')->unsigned();
            $table->integer('products_amount');
        });

        Schema::table('attributes_options_products_amount', function(Blueprint $table) {
            $table->foreign('category_id', 'category_index')->references('id')->on('categories');
            $table->foreign('option_id', 'option_index')->references('id')->on('attribute_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes_options_products_amount');
    }
}
