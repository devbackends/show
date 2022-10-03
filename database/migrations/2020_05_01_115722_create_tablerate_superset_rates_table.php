<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablerateSupersetRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tablerate_superset_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price_from', 12, 4)->unsigned();
            $table->decimal('price_to', 12, 4)->unsigned();
            $table->decimal('price', 12, 4)->default(0);
            $table->string('shipping_type');
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
        Schema::dropIfExists('tablerate_superset_rates');
    }
}
