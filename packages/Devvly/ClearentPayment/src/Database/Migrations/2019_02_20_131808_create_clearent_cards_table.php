<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class  CreateClearentCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearent_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customers_id')->nullable()->unsigned();
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('cascade');
            $table->text('jwt_token');
            $table->string('card_type');
            $table->string('last_four');
            $table->string('exp_date')->nullable();
            $table->text('card_token')->nullable();
            $table->tinyInteger('save')->nullable();
            $table->integer('is_default')->nullable();
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
        Schema::dropIfExists('clearent_cards');
    }
}
