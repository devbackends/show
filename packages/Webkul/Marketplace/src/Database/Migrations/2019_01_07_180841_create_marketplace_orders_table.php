<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->boolean('is_withdrawal_requested')->default(0);
            $table->string('seller_payout_status')->nullable();

            $table->decimal('commission_percentage', 12, 4)->default(0)->nullable();
            $table->decimal('commission', 12, 4)->default(0)->nullable();
            $table->decimal('base_commission', 12, 4)->default(0)->nullable();
            $table->decimal('commission_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_commission_invoiced', 12, 4)->default(0)->nullable();

            $table->decimal('seller_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_seller_total', 12, 4)->default(0)->nullable();
            $table->decimal('seller_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_seller_total_invoiced', 12, 4)->default(0)->nullable();

            $table->integer('total_item_count')->nullable();
            $table->integer('total_qty_ordered')->nullable();

            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('grand_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('grand_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('discount_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('discount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('discount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('shipping_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_amount', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_refunded', 12, 4)->default(0)->nullable();

            $table->integer('marketplace_seller_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->foreign('marketplace_seller_id')->references('id')->on('marketplace_sellers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unique(['marketplace_seller_id', 'order_id']);
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
        Schema::dropIfExists('marketplace_orders');
    }
}
