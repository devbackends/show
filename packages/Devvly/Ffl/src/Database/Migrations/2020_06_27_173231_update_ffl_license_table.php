<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFflLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ffl_license', function (Blueprint $table) {
            $table->index('license_number');
            $table->string('license_img')->nullable()->change();
            $table->renameColumn('license_img', 'license_file');
            $table->string('license_name')->nullable()->after('license_number');
            $table->char('license_region', 1)->nullable()->after('license_name');
            $table->char('license_district', 2)->nullable()->after('license_region');
            $table->integer('license_fips')->nullable()->after('license_district');
            $table->char('license_type', 2)->nullable()->after('license_fips');
            $table->char('license_expire_date', 2)->nullable()->after('license_type');
            $table->index('license_expire_date');
            $table->integer('license_sequence')->nullable()->after('license_expire_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ffl_license', function (Blueprint $table) {
            $table->dropIndex(['license_number']);
            $table->dropIndex(['license_expire_date']);
            $table->string('license_file')->nullable(true)->change();
            $table->renameColumn('license_file', 'license_img');
            $table->dropColumn('license_district');
            $table->dropColumn('license_region');
            $table->dropColumn('license_fips');
            $table->dropColumn('license_type');
            $table->dropColumn('license_expire_date');
            $table->dropColumn('license_sequence');
            $table->dropColumn('license_name');
        });
    }
}
