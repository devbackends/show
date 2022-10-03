<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_shipment_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('marketplace_shipment_id')->unsigned();
            $table->foreign('marketplace_shipment_id')->references('id')->on('marketplace_shipments')->onDelete('cascade');

            $table->integer('shipment_item_id')->unsigned();
            $table->foreign('shipment_item_id')->references('id')->on('shipment_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_shipment_items');
    }
}