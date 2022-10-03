<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFflBusinessInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ffl_business_info', function (Blueprint $table) {
            $table->decimal('latitude', 9, 6)->nullable()->after('business_hours');
            $table->decimal('longitude', 9, 6)->nullable()->after('latitude');
            $table->dropUnique('ffl_business_info_email_unique');
            $table->string('email')->nullable()->change();
            $table->integer('state')->unsigned()->nullable();
            $table->foreign('state')->on('country_states')->references('id')->onDelete('set null');
            $table->string('website')->nullable()->change();
            $table->boolean('retail_store_front')->nullable()->change();
            $table->boolean('importer_exporter')->nullable()->change();
            $table->string('phone')->change();
            $table->dropUnique('ffl_business_info_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ffl_business_info', function (Blueprint $table) {
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
            $table->dropForeign('ffl_business_info_state_foreign');
            $table->dropColumn('state');
            $table->boolean('retail_store_front')->nullable(false)->change();
            $table->boolean('importer_exporter')->nullable(false)->change();
            $table->string('website')->nullable(false)->change();
            $table->string('email')->nullable(false)->unique()->change();
            $table->integer('phone')->unique()->change();
        });
    }
}
