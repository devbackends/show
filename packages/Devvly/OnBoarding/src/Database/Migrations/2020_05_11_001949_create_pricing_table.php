<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreatePricingTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::dropIfExists('pricing');
    Schema::create('pricing', function (Blueprint $table) {
      $table->increments('id');
      $table->string('card_type');
      $table->string('card_type_settlement');
      $table->float('flat_rate')->default(0);
      $table->float('express_merchant_funding')->default(0);
      $table->string('settlement');
      $table->float('whalut_fee')->default(0);
      $table->float('authorization_fee')->default(0);
      $table->float('capture_settlement_fee')->default(0);
      $table->float('american_express_auth_fee')->default(0);
      $table->float('pin_based_debit_transaction_fee')->default(0);
      $table->float('ebt_transaction_fee')->default(0);
      $table->float('batch_fee')->default(0);
      $table->float('ivr')->default(0);
      $table->float('voice_authorization')->default(0);
      $table->float('monthly_statement_fee')->default(0);
      $table->float('monthly_account_fee')->default(0);
      $table->float('monthly_help_desk_fee')->default(0);
      $table->float('monthly_compass_fee')->default(0);
      $table->float('monthly_data_guardian_fee')->default(0);
      $table->float('non_complete_pci_fee')->default(0);
      $table->float('end_billing_option_fee')->default(0);
      $table->float('quest_virtual_terminal')->default(0);
      $table->float('paylink')->default(0);
      $table->float('setup_fee')->default(0);
      $table->float('update_fee')->default(0);
      $table->float('annual_regulatory_reporting_fee')->default(0);
      $table->float('chargback_fee')->default(0);
      $table->float('retrieval_fee')->default(0);
      $table->float('keyed_application_fee')->default(0);
      $table->float('inactivity_fee')->default(0);
      $table->float('discount_billed_to_merchant')->default(0);
      $table->float('helpdesk_calls_for_non_supported_terminals')->default(0);
      $table->float('voyager_capture')->default(0);
      $table->float('wright_express')->default(0);
      $table->float('monthly_wireless_access_fee')->default(0);
      $table->float('express_merchant_funding_fee')->default(0);
      $table->float('host_capture_fees')->default(0);
      $table->float('host_capture_monthly_fee')->default(0);
      $table->float('host_capture_transaction_fee')->default(0);
      $table->float('host_capture_administrative_transaction_fee')->default(0);
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
    Schema::dropIfExists('pricing');
  }
}
