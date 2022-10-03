<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFflToCartAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_address', function (Blueprint $table) {
            $table->bigInteger('ffl_id')->after('customer_id')->nullable()->unsigned();
            $table->foreign('ffl_id')->references('id')->on('ffl');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_address', function (Blueprint $table) {
            $table->dropForeign(['ffl_id']);
            $table->dropColumn('ffl_id');
        });
    }
}
