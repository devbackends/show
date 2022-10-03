<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeroImageLinkVelocityMetaData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('velocity_meta_data', function(Blueprint $table){
            $table->longText('hero_image_link')->nullable();
        });

        Schema::table('cms_page_translations', function(Blueprint $table){
            $table->longText('hero_image_link')->nullable();
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
            $table->dropColumn('hero_image_link');
        });

        Schema::table('cms_page_translations', function (Blueprint $table) {
            $table->dropColumn('hero_image_link');
        });
    }
}
