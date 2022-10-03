<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->nullable();
            $table->json('dates')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('location', 255)->nullable();
            $table->json('hours')->nullable();
            $table->string('admission', 255)->nullable();
            $table->string('hash_index')->index()->comment('backend generated hash for comparison');
            $table->bigInteger('promoter_id')->unsigned();
            $table->boolean('is_cancelled')->default(false);
            $table->timestamps();
        });

        Schema::table('shows', function(Blueprint $table) {
            $table->foreign('promoter_id', 'show_promoters_index')->references('id')->on('shows_promoters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('shows');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
