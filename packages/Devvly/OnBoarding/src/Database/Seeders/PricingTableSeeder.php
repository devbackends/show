<?php

namespace Devvly\OnBoarding\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;


class PricingTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('pricing')->delete();

        DB::table('pricing')->insert([
            [
                'card_type' => '1',
                'card_type_settlement' => '2',
                'flat_rate' => 2.72,
                'express_merchant_funding' => 0.04,
                'settlement' =>'8',
                'whalut_fee' => 17,
                'authorization_fee' => 0.3,
                'capture_settlement_fee' => 8,
                'american_express_auth_fee' => 0,
                'pin_based_debit_transaction_fee' => 0,
                'ebt_transaction_fee' => 0,
                'batch_fee' => 0,
                'ivr' => 0.5,
                'voice_authorization' => 1,
                'monthly_statement_fee' => 0,
                'monthly_account_fee' => 10,
                'monthly_help_desk_fee' => 0,
                'monthly_compass_fee' => 0,
                'monthly_data_guardian_fee' => 12,
                'non_complete_pci_fee' => 25,
                'end_billing_option_fee' => 0,
                'quest_virtual_terminal' => 0,
                'paylink' => 0,
                'setup_fee' => 0,
                'update_fee' => 0,
                'annual_regulatory_reporting_fee' => 0,
                'chargback_fee' => 25,
                'retrieval_fee' => 10,
                'keyed_application_fee' => 0,
                'inactivity_fee' => 0,
                'discount_billed_to_merchant' => 0,
                'helpdesk_calls_for_non_supported_terminals' => 0,
                'voyager_capture' => 0,
                'wright_express' => 0,
                'monthly_wireless_access_fee' => 0,
                'express_merchant_funding_fee' => 0,
                'host_capture_fees' => 0,
                'host_capture_monthly_fee' => 0,
                'host_capture_transaction_fee' => 0,
                'host_capture_administrative_transaction_fee' => 0



            ]
            ]);

    }
}