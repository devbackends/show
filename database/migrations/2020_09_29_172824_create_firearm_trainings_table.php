<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirearmTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firearm_trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_course_id', 100)->nullable();
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('instructor')->nullable();
            $table->string('external_instructor_id')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->float('cost')->nullable();
            $table->string('idw_cost')->nullable();
            $table->string('hours')->nullable();
            $table->string('class_time')->nullable();
            $table->string('gender')->nullable();
            $table->string('offering')->nullable();
            $table->string('spans')->nullable();
            $table->string('granted')->nullable();
            $table->string('publish_notes')->nullable();
            $table->dateTime('date_stamp')->nullable();
            $table->dateTime('class_date')->nullable();
            $table->boolean('approved')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city_name')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->boolean('is_course_blended')->nullable();
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
        Schema::dropIfExists('firearm_trainings');
    }
}
