<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_refunds', function (Blueprint $table) {
            $table->increments('id');

            $table->string('increment_id')->nullable();
            $table->string('state')->nullable();
            $table->boolean('email_sent')->default(0);

            $table->integer('total_qty')->nullable();

            $table->decimal('adjustment_refund', 12, 4)->default(0)->nullable();
            $table->decimal('base_adjustment_refund', 12, 4)->default(0)->nullable();

            $table->decimal('adjustment_fee', 12, 4)->default(0)->nullable();
            $table->decimal('base_adjustment_fee', 12, 4)->default(0)->nullable();

            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();

            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();

            $table->decimal('shipping_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_amount', 12, 4)->default(0)->nullable();

            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount', 12, 4)->default(0)->nullable();

            $table->decimal('discount_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_amount', 12, 4)->default(0)->nullable();

            $table->integer('refund_id')->unsigned()->nullable();
            $table->foreign('refund_id')->references('id')->on('refunds')->onDelete('cascade');

            $table->integer('marketplace_order_id')->unsigned()->nullable();
            $table->foreign('marketplace_order_id')->references('id')->on('marketplace_orders')->onDelete('cascade');

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
        Schema::dropIfExists('marketplace_refunds');
    }
}
