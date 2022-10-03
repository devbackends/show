<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSearchSuggestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_search_suggestion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manufacturer')->nullable();
            $table->string('caliber')->nullable();
            $table->string('gauge')->nullable();
            $table->string('baarell_length')->nullable();
            $table->string('year')->nullable();
            $table->string('finish')->nullable();
            $table->string('capacity')->nullable();
            $table->string('overall_length')->nullable();
            $table->string('weight')->nullable();
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
        Schema::dropIfExists('product_search_suggestion');

    }
}
