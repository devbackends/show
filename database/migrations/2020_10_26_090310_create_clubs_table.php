<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 100)->nullable();
            $table->string('org_desc')->nullable();
            $table->string('club_name')->nullable();
            $table->dateTime('origination_order_date')->nullable();
            $table->string('address_type_desc')->nullable();
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country_id')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('web')->nullable();
            $table->string('mailing_flag')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->float('distance')->nullable();
            $table->string('hash_index')->index()->comment('backend generated hash for comparison');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clubs');
    }
}
