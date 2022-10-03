<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablerateShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tablerate_shipping_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->boolean('is_zip_range')->default(0);
            $table->string('zip_from')->nullable();
            $table->string('zip_to')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('price', 12, 4)->default(0);
            $table->decimal('weight_from', 12, 4)->default(0);
            $table->decimal('weight_to', 12, 4)->default(0);

            $table->integer('tablerate_superset_id')->unsigned()->nullable();
            $table->foreign('tablerate_superset_id')->references('id')->on('tablerate_supersets')->onDelete('cascade');
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
        Schema::dropIfExists('tablerate_shipping_rates');
    }
}
