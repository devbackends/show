<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_order_items', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('commission', 12, 4)->default(0)->nullable();
            $table->decimal('base_commission', 12, 4)->default(0)->nullable();

            $table->decimal('commission_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_commission_invoiced', 12, 4)->default(0)->nullable();

            $table->decimal('seller_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_seller_total', 12, 4)->default(0)->nullable();

            $table->decimal('seller_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_seller_total_invoiced', 12, 4)->default(0)->nullable();

            $table->integer('order_item_id')->unsigned();

            $table->integer('marketplace_order_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->foreign('marketplace_order_id')->references('id')->on('marketplace_orders')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('marketplace_order_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_order_items');
    }
}