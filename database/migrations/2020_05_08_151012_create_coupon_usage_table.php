<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('times_used')->unsigned()->default(0);

            $table->integer('coupon_id')->unsigned();
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');

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
        Schema::dropIfExists('coupon_usage');
    }
}
