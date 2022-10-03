<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActionRemoveUnusableTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('marketplace_import_new_products_by_admin');
        Schema::dropIfExists('marketplace_import_new_products');
        Schema::dropIfExists('marketplace_bulkupload_admin_dataflowprofile');
        Schema::dropIfExists('marketplace_bulkupload_dataflowprofile');

        Schema::dropIfExists('marketplace_rma_images');
        Schema::dropIfExists('marketplace_rma_items');
        Schema::dropIfExists('marketplace_rma_messages');
        Schema::dropIfExists('marketplace_rma_reasons');

        Schema::dropIfExists('marketplace_rma');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_import_new_products_by_admin');
        Schema::dropIfExists('marketplace_import_new_products');
        Schema::dropIfExists('marketplace_bulkupload_admin_dataflowprofile');
        Schema::dropIfExists('marketplace_bulkupload_dataflowprofile');

        Schema::dropIfExists('marketplace_rma_images');
        Schema::dropIfExists('marketplace_rma_items');
        Schema::dropIfExists('marketplace_rma_messages');
        Schema::dropIfExists('marketplace_rma_reasons');

        Schema::dropIfExists('marketplace_rma');

    }
}
