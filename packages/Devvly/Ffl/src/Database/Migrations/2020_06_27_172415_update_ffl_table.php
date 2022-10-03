<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFflTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ffl', function (Blueprint $table) {
            $table->text('source')->after('id');
            $table->boolean('is_approved')->after('source')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ffl', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            $table->dropColumn('source');
        });
    }
}
