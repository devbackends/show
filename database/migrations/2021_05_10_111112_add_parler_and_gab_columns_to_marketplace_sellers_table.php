<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParlerAndGabColumnsToMarketplaceSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketplace_sellers', function (Blueprint $table) {
            $table->string('parler')->after('instagram')->nullable();
            $table->string('gab')->after('parler')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketplace_sellers', function (Blueprint $table) {
            $table->dropColumn('parler');
            $table->dropColumn('gab');
        });
    }
}
