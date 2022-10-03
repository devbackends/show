<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsrLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsr_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('import_distribute')->default(0);
            $table->dateTime('import_latest_run');
            $table->boolean('update_rsr_distribute')->default(0);
            $table->dateTime('update_latest_run');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsr_logs');
    }
}
