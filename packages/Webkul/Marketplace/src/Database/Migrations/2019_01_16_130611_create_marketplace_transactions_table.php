<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('transaction_id')->unique();
            $table->string('method')->nullable();
            $table->text('comment')->nullable();

            $table->decimal('base_total', 12, 4)->default(0)->nullable();

            $table->integer('marketplace_seller_id')->unsigned();
            $table->integer('marketplace_order_id')->unsigned();
            $table->foreign('marketplace_seller_id')->references('id')->on('marketplace_sellers')->onDelete('cascade');
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
        Schema::dropIfExists('marketplace_transactions');
    }
}
