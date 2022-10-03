<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingMethodColumnToMarketplaceOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->string('shipping_method')->nullable()->after('base_tax_amount_refunded');
            $table->string('shipping_title')->nullable()->after('shipping_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketplace_orders', function($table) {
            $table->dropColumn('shipping_method');
            $table->dropColumn('shipping_title');
        });
    }
}
