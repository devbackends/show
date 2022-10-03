<?php

namespace Devvly\OnBoarding\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\ChannelProxy;

class Pricing extends Model
{
    use Notifiable;

    protected $table = 'pricing';


    protected $fillable = ['card_type','card_type_settlement','flat_rate','express_merchant_funding','settlement','whalut_fee','authorization_fee','capture_settlement_fee','american_express_auth_fee','pin_based_debit_transaction_fee','ebt_transaction_fee','batch_fee','ivr','voice_authorization','monthly_statement_fee','monthly_account_fee','monthly_help_desk_fee','monthly_compass_fee','monthly_data_guardian_fee','non_complete_pci_fee','end_billing_option_fee','quest_virtual_terminal','paylink','setup_fee','update_fee','annual_regulatory_reporting_fee','chargback_fee','retrieval_fee','keyed_application_fee','inactivity_fee','discount_billed_to_merchant','helpdesk_calls_for_non_supported_terminals','voyager_capture','wright_express','monthly_wireless_access_fee','express_merchant_funding_fee','host_capture_fees','host_capture_monthly_fee','host_capture_transaction_fee','host_capture_administrative_transaction_fee'];

}