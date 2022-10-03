<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeroImageVelocityMetaData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('velocity_meta_data', function(Blueprint $table){
            $table->string('path_hero_image');
        });

        Schema::table('cms_page_translations', function(Blueprint $table){
            $table->string('path_hero_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->dropColumn('path_hero_image');
        });

        Schema::table('cms_page_translations', function (Blueprint $table) {
            $table->dropColumn('path_hero_image');
        });
    }
}
