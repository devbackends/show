<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceMessageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_message_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body');
            $table->string('message_type')->default('text');
            $table->boolean('read')->default(0);
            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')->references('id')->on('marketplace_messages')->onDelete('cascade');
            $table->integer('from')->unsigned();
            $table->foreign('from')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('to')->unsigned();
            $table->foreign('to')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('marketplace_message_details');
    }
}