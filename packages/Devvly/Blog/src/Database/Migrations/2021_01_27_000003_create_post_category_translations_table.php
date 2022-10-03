<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_c_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('locale')->nullable();
            $table->string('url_key')->nullable();
            $table->integer('post_category_id')->unsigned();
            $table->foreign('post_category_id')->references('id')->on('post_categories')->onDelete('cascade');
            $table->unique(['name', 'url_key', 'post_category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('post_c_translations');
        Schema::enableForeignKeyConstraints();
    }
}
