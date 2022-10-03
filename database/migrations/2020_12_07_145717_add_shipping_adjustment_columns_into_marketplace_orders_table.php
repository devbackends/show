<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingAdjustmentColumnsIntoMarketplaceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->float('base_shipment_processing_invoiced')->after('base_commission_invoiced')->nullable();
            $table->float('shipment_processing_invoiced')->after('base_commission_invoiced')->nullable();
            $table->float('base_shipment_processing')->after('base_commission_invoiced')->nullable();
            $table->float('shipment_processing')->after('base_commission_invoiced')->nullable();
            $table->float('shipment_processing_percentage')->after('base_commission_invoiced')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->dropColumn('base_shipment_processing_invoiced');
            $table->dropColumn('shipment_processing_invoiced');
            $table->dropColumn('base_shipment_processing');
            $table->dropColumn('shipment_processing');
            $table->dropColumn('shipment_processing_percentage');
        });
    }
}
