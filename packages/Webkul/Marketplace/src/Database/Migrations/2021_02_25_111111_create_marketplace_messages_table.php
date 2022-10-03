<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->boolean('read')->default(0);
            $table->boolean('spam')->default(0);
            $table->boolean('trash')->default(0);
            $table->boolean('archive')->default(0);

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
        Schema::dropIfExists('marketplace_messages');
    }
}
