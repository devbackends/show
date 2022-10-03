<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketplaceMessageReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_message_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->string('reason');
            $table->string('details');
            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')->references('id')->on('marketplace_messages')->onDelete('cascade');
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
        Schema::dropIfExists('marketplace_message_reports');
    }
}