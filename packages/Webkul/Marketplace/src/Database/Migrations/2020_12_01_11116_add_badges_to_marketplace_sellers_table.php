<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBadgesToMarketplaceSellersTable extends Migration
{
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
    {
                Schema::table('marketplace_sellers', function (Blueprint $table) {
                        $table->boolean('nra_certified')->default(0);
                        $table->boolean('uscca_certified')->default(0);
                        $table->boolean('general_events_certified')->default(0);
                        $table->string('certification')->nullable();
                        $table->string('instructor_number')->nullable();
                        $table->string('instructor_description')->nullable();
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
