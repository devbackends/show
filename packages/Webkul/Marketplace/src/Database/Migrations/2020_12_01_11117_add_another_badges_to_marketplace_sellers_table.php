<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnotherBadgesToMarketplaceSellersTable extends Migration
{
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
    {
                Schema::table('marketplace_sellers', function (Blueprint $table) {
                        $table->boolean('retailer_badge')->default(0);
                        $table->boolean('competition_shooter_badge')->default(0);
                        $table->boolean('promotor_badge')->default(0);
                        $table->boolean('veteran_badge')->default(0);
                        $table->boolean('influencer_badge')->default(0);
                    });
            }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            }
}
