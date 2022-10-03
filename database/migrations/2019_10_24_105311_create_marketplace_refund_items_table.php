<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceRefundItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_refund_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('marketplace_refund_id')->unsigned();
            $table->foreign('marketplace_refund_id')->references('id')->on('marketplace_refunds')->onDelete('cascade');

            $table->integer('refund_item_id')->unsigned();
            $table->foreign('refund_item_id')->references('id')->on('refund_items')->onDelete('cascade');

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
        Schema::dropIfExists('marketplace_refund_items');
    }
}
