<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestructureCompanyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_channel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('domain')->unique();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('meta_title');
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('use_seo')->default(0);
            $table->json('misc')->nullable();
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
        Schema::dropIfExists('super_channel');

        Schema::dropIfExists('super_currencies');

        Schema::dropIfExists('super_locales');
    }
}
