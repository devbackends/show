<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPbPageIdToPostTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $prefix = config('pagebuilder.storage.database.prefix');
            $table->integer('pb_page_id')->unsigned()->nullable();
            $table->foreign('pb_page_id')->references('id')->on($prefix . 'pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('post_translations', function (Blueprint $table) {
           $table->dropForeign('pg_page_id');
           $table->dropColumn('pg_page_id');
       });
    }
}
