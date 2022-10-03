@extends('saas::super.layouts.content')

@section('page_title')
   Update Pricing
@endsection

@section('content')

    <pricing-registration></pricing-registration>

    @push('scripts')
        <script type="text/x-template" id ="pricing-details-form">

            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" ></i>
                            Update Pricing
                        </h1>
                    </div>
                </div>

                <div class="page-content">
                    <form method="POST" action="{{ route('super.predefined.pricing.store') }}">
                        @csrf

                        <div class="control-group pricing_element_container" :class="[errors.has('flat_rate') ? 'has-error' : '']">
                            <label for="flat_rate" class="required">Flat Rate</label>
                            <input type="text" v-model="flat_rate" v-validate="'required|numeric'"  class="control"  placeholder="Flat Rate" name="flat_rate" data-vv-as="&quot; Flat Rate &quot;">
                            <span class="control-error" v-if="errors.has('flat_rate')">@{{ errors.first('flat_rate') }}</span>
                        </div>
                        <div class="control-group pricing_element_container" :class="[errors.has('express_merchant_funding') ? 'has-error' : '']">
                            <label for="express_merchant_funding" class="required">Express Merchant Funding</label>
                            <input type="text" v-model="express_merchant_funding" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Express Merchant Funding" name="express_merchant_funding" data-vv-as="&quot; Express Merchant Funding &quot;">
                            <span class="control-error" v-if="errors.has('express_merchant_funding')">@{{ errors.first('express_merchant_funding') }}</span>
                        </div>
                        <div class="control-group pricing_element_container" :class="[errors.has('settlement') ? 'has-error' : '']">
                            <label for="settlement" class="required">Settlement</label>
                            <input type="text" v-model="settlement" v-validate="'required|string'"  class="control"  placeholder="Settlement" name="settlement" data-vv-as="&quot; Settlement &quot;">
                            <span class="control-error" v-if="errors.has('settlement')">@{{ errors.first('settlement') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('whalut_fee') ? 'has-error' : '']">
                            <label for="whalut_fee" class="required">Whalut Fee</label>
                            <input type="text" v-model="whalut_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Whalut Fee" name="whalut_fee" data-vv-as="&quot; Whalut Fee &quot;">
                            <span class="control-error" v-if="errors.has('whalut_fee')">@{{ errors.first('whalut_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('authorization_fee') ? 'has-error' : '']">
                            <label for="authorization_fee" class="required">Authorization Fee</label>
                            <input type="text" v-model="authorization_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Authorization Fee" name="authorization_fee" data-vv-as="&quot; Authorization Fee &quot;">
                            <span class="control-error" v-if="errors.has('authorization_fee')">@{{ errors.first('authorization_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('capture_settlement_fee') ? 'has-error' : '']">
                            <label for="capture_settlement_fee" class="required">Capture Settlement Fee</label>
                            <input type="text" v-model="capture_settlement_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Capture Settlement Fee" name="capture_settlement_fee" data-vv-as="&quot; Capture Settlement Fee &quot;">
                            <span class="control-error" v-if="errors.has('capture_settlement_fee')">@{{ errors.first('capture_settlement_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('american_express_auth_fee') ? 'has-error' : '']">
                            <label for="american_express_auth_fee" class="required">American Express Auth Fee</label>
                            <input type="text" v-model="american_express_auth_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="American Express Auth Fee" name="american_express_auth_fee" data-vv-as="&quot; American Express Auth Fee &quot;">
                            <span class="control-error" v-if="errors.has('american_express_auth_fee')">@{{ errors.first('american_express_auth_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('pin_based_debit_transaction_fee') ? 'has-error' : '']">
                            <label for="pin_based_debit_transaction_fee" class="required">Pin Based Debit Transaction Fee</label>
                            <input type="text" v-model="pin_based_debit_transaction_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Pin Based Debit Transaction Fee" name="pin_based_debit_transaction_fee" data-vv-as="&quot; Pin Based Debit Transaction Fee &quot;">
                            <span class="control-error" v-if="errors.has('pin_based_debit_transaction_fee')">@{{ errors.first('pin_based_debit_transaction_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('ebt_transaction_fee') ? 'has-error' : '']">
                            <label for="ebt_transaction_fee" class="required">EBT Transaction Fee</label>
                            <input type="text" v-model="ebt_transaction_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="EBT Transaction Fee" name="ebt_transaction_fee" data-vv-as="&quot; EBT Transaction Fee &quot;">
                            <span class="control-error" v-if="errors.has('ebt_transaction_fee')">@{{ errors.first('ebt_transaction_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('batch_fee') ? 'has-error' : '']">
                            <label for="batch_fee" class="required">Batch Fee</label>
                            <input type="text" v-model="batch_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Batch Fee" name="batch_fee" data-vv-as="&quot; Batch Fee &quot;">
                            <span class="control-error" v-if="errors.has('batch_fee')">@{{ errors.first('batch_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('ivr') ? 'has-error' : '']">
                            <label for="ivr" class="required">IVR</label>
                            <input type="text" v-model="ivr" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="IVR" name="ivr" data-vv-as="&quot; IVR &quot;">
                            <span class="control-error" v-if="errors.has('ivr')">@{{ errors.first('ivr') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('voice_authorization') ? 'has-error' : '']">
                            <label for="voice_authorization" class="required">Voice Authorization</label>
                            <input type="text" v-model="voice_authorization" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Voice Authorization" name="voice_authorization" data-vv-as="&quot; Voice Authorization &quot;">
                            <span class="control-error" v-if="errors.has('voice_authorization')">@{{ errors.first('voice_authorization') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('monthly_statement_fee') ? 'has-error' : '']">
                            <label for="monthly_statement_fee" class="required">Monthly Statement Fee</label>
                            <input type="text" v-model="monthly_statement_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Monthly Statement Fee" name="monthly_statement_fee" data-vv-as="&quot; Monthly Statement Fee &quot;">
                            <span class="control-error" v-if="errors.has('monthly_statement_fee')">@{{ errors.first('monthly_statement_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('monthly_account_fee') ? 'has-error' : '']">
                            <label for="monthly_account_fee" class="required">Monthly Account Fee</label>
                            <input type="text" v-model="monthly_account_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Monthly Account Fee" name="monthly_account_fee" data-vv-as="&quot; Monthly Account Fee &quot;">
                            <span class="control-error" v-if="errors.has('monthly_account_fee')">@{{ errors.first('monthly_account_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('monthly_help_desk_fee') ? 'has-error' : '']">
                            <label for="monthly_help_desk_fee" class="required">Monthly Helpdesk Fee</label>
                            <input type="text" v-model="monthly_help_desk_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Monthly Helpdesk Fee" name="monthly_help_desk_fee" data-vv-as="&quot; Monthly Helpdesk Fee &quot;">
                            <span class="control-error" v-if="errors.has('monthly_help_desk_fee')">@{{ errors.first('monthly_help_desk_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('monthly_compass_fee') ? 'has-error' : '']">
                            <label for="monthly_compass_fee" class="required">Monthly Compass Fee</label>
                            <input type="text" v-model="monthly_compass_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Monthly Compass Fee" name="monthly_compass_fee" data-vv-as="&quot; Monthly Compass Fee &quot;">
                            <span class="control-error" v-if="errors.has('monthly_compass_fee')">@{{ errors.first('monthly_compass_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('monthly_data_guardian_fee') ? 'has-error' : '']">
                            <label for="monthly_data_guardian_fee" class="required">Monthly Data Gardian Fee</label>
                            <input type="text" v-model="monthly_data_guardian_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Monthly Data Gardian Fee" name="monthly_data_guardian_fee" data-vv-as="&quot; Monthly Data Gardian Fee &quot;">
                            <span class="control-error" v-if="errors.has('monthly_data_guardian_fee')">@{{ errors.first('monthly_data_guardian_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('non_complete_pci_fee') ? 'has-error' : '']">
                            <label for="non_complete_pci_fee" class="required">Non Complete Pci Fee</label>
                            <input type="text" v-model="non_complete_pci_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Non Complete PCI Fee" name="non_complete_pci_fee" data-vv-as="&quot; Non Complete PCI Fee &quot;">
                            <span class="control-error" v-if="errors.has('non_complete_pci_fee')">@{{ errors.first('non_complete_pci_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('end_billing_option_fee') ? 'has-error' : '']">
                            <label for="end_billing_option_fee" class="required">End Billing Option Fee</label>
                            <input type="text" v-model="end_billing_option_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="End Billing Option Fee" name="end_billing_option_fee" data-vv-as="&quot; End Billing Option Fee &quot;">
                            <span class="control-error" v-if="errors.has('end_billing_option_fee')">@{{ errors.first('end_billing_option_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('quest_virtual_terminal') ? 'has-error' : '']">
                            <label for="quest_virtual_terminal" class="required">Quest Virtual Terminal</label>
                            <input type="text" v-model="quest_virtual_terminal" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Quest Virtual Terminal" name="quest_virtual_terminal" data-vv-as="&quot; Quest Virtual Terminal &quot;">
                            <span class="control-error" v-if="errors.has('quest_virtual_terminal')">@{{ errors.first('quest_virtual_terminal') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('paylink') ? 'has-error' : '']">
                            <label for="paylink" class="required">Paylink</label>
                            <input type="text" v-model="paylink" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Paylink" name="paylink" data-vv-as="&quot; paylink &quot;">
                            <span class="control-error" v-if="errors.has('paylink')">@{{ errors.first('paylink') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('setup_fee') ? 'has-error' : '']">
                            <label for="setup_fee" class="required">Setup Fee</label>
                            <input type="text" v-model="setup_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Setup Fee" name="setup_fee" data-vv-as="&quot; Setup Fee &quot;">
                            <span class="control-error" v-if="errors.has('setup_fee')">@{{ errors.first('setup_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('update_fee') ? 'has-error' : '']">
                            <label for="update_fee" class="required">Update Fee</label>
                            <input type="text" v-model="update_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Update Fee" name="update_fee" data-vv-as="&quot; Update Fee &quot;">
                            <span class="control-error" v-if="errors.has('update_fee')">@{{ errors.first('update_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('annual_regulatory_reporting_fee') ? 'has-error' : '']">
                            <label for="update_fee" class="required">Annual Regulatory Reporting Fee</label>
                            <input type="text" v-model="annual_regulatory_reporting_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Annual Regulatory Reporting Fee" name="annual_regulatory_reporting_fee" data-vv-as="&quot; Annual Regulatory Reporting Fee &quot;">
                            <span class="control-error" v-if="errors.has('annual_regulatory_reporting_fee')">@{{ errors.first('annual_regulatory_reporting_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('chargback_fee') ? 'has-error' : '']">
                            <label for="chargback_fee" class="required">Chargback Fee</label>
                            <input type="text" v-model="chargback_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Chargback Fee" name="chargback_fee" data-vv-as="&quot; Chargback Fee &quot;">
                            <span class="control-error" v-if="errors.has('chargback_fee')">@{{ errors.first('chargback_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('retrieval_fee') ? 'has-error' : '']">
                            <label for="retrieval_fee" class="required">Retrieval Fee</label>
                            <input type="text" v-model="retrieval_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Retrieval Fee" name="retrieval_fee" data-vv-as="&quot; Retrieval Fee &quot;">
                            <span class="control-error" v-if="errors.has('retrieval_fee')">@{{ errors.first('retrieval_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('keyed_application_fee') ? 'has-error' : '']">
                            <label for="keyed_application_fee" class="required">Keyed Application Fee</label>
                            <input type="text" v-model="keyed_application_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Keyed Application Fee" name="keyed_application_fee" data-vv-as="&quot; Keyed Application Fee &quot;">
                            <span class="control-error" v-if="errors.has('keyed_application_fee')">@{{ errors.first('keyed_application_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('inactivity_fee') ? 'has-error' : '']">
                            <label for="inactivity_fee" class="required">Inactivity Fee</label>
                            <input type="text" v-model="inactivity_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Inactivity Fee" name="inactivity_fee" data-vv-as="&quot; Inactivity Fee &quot;">
                            <span class="control-error" v-if="errors.has('inactivity_fee')">@{{ errors.first('inactivity_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('discount_billed_to_merchant') ? 'has-error' : '']">
                            <label for="discount_billed_to_merchant" class="required">Discount Billed To Merchant</label>
                            <input type="text" v-model="discount_billed_to_merchant" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Discount Billed To Merchant" name="discount_billed_to_merchant" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('discount_billed_to_merchant')">@{{ errors.first('discount_billed_to_merchant') }}</span>
                        </div>


                        <div class="control-group pricing_element_container" :class="[errors.has('helpdesk_calls_for_non_supported_terminals') ? 'has-error' : '']">
                            <label for="helpdesk_calls_for_non_supported_terminals" class="required">Helpdesk Calls For Non Supported Terminals</label>
                            <input type="text" v-model="helpdesk_calls_for_non_supported_terminals" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Helpdesk Calls For Non Supported Terminals" name="helpdesk_calls_for_non_supported_terminals" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('helpdesk_calls_for_non_supported_terminals')">@{{ errors.first('helpdesk_calls_for_non_supported_terminals') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('voyager_capture') ? 'has-error' : '']">
                            <label for="voyager_capture" class="required">Voyager Capture</label>
                            <input type="text" v-model="voyager_capture" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Voyager Capture" name="voyager_capture" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('voyager_capture')">@{{ errors.first('voyager_capture') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('wright_express') ? 'has-error' : '']">
                            <label for="wright_express" class="required">Wright Express</label>
                            <input type="text" v-model="wright_express" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Wright Express" name="wright_express" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('wright_express')">@{{ errors.first('wright_express') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('monthly_wireless_access_fee') ? 'has-error' : '']">
                            <label for="monthly_wireless_access_fee" class="required">Monthly Wireless Access Fee</label>
                            <input type="text" v-model="monthly_wireless_access_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Monthly Wireless Access Fee" name="monthly_wireless_access_fee" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('monthly_wireless_access_fee')">@{{ errors.first('monthly_wireless_access_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('express_merchant_funding_fee') ? 'has-error' : '']">
                            <label for="express_merchant_funding_fee" class="required">Express Merchant Funding Fee</label>
                            <input type="text" v-model="express_merchant_funding_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Express Merchant Funding Fee" name="express_merchant_funding_fee" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('express_merchant_funding_fee')">@{{ errors.first('express_merchant_funding_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('host_capture_fees') ? 'has-error' : '']">
                            <label for="host_capture_fees" class="required">Express Merchant Funding Fee</label>
                            <input type="text" v-model="host_capture_fees" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Host Capture Fee" name="host_capture_fees" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('host_capture_fees')">@{{ errors.first('host_capture_fees') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('host_capture_monthly_fee') ? 'has-error' : '']">
                            <label for="host_capture_monthly_fee" class="required">Host Capture Monthly Fee</label>
                            <input type="text" v-model="host_capture_monthly_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Host Capture Monthly Fee" name="host_capture_monthly_fee" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('host_capture_monthly_fee')">@{{ errors.first('host_capture_monthly_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('host_capture_transaction_fee') ? 'has-error' : '']">
                            <label for="host_capture_transaction_fee" class="required">Host Capture Transaction Fee</label>
                            <input type="text" v-model="host_capture_transaction_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Host Capture Transaction Fee" name="host_capture_transaction_fee" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('host_capture_transaction_fee')">@{{ errors.first('host_capture_transaction_fee') }}</span>
                        </div>

                        <div class="control-group pricing_element_container" :class="[errors.has('host_capture_administrative_transaction_fee') ? 'has-error' : '']">
                            <label for="host_capture_administrative_transaction_fee" class="required">Host Capture Administrative Transaction Fee</label>
                            <input type="text" v-model="host_capture_administrative_transaction_fee" v-validate="'required|numeric|min:0|max:100'"  class="control"  placeholder="Host Capture Administrartive Transaction Fee" name="host_capture_administrative_transaction_fee" data-vv-as="&quot;  &quot;">
                            <span class="control-error" v-if="errors.has('host_capture_administrative_transaction_fee')">@{{ errors.first('host_capture_administrative_transaction_fee') }}</span>
                        </div>

                        <button  class="btn btn-primary">
                            {{ __('saas::app.super-user.tenants.btn-update') }}
                        </button>
                    </form>
                </div>
            </div>
            </script>
            <script>
                Vue.component('pricing-registration', {
                    template: '#pricing-details-form',
                    inject: ['$validator'],

                    data: () => ({
                    flat_rate: '{{ $pricing->flat_rate }}',
                    express_merchant_funding: '{{ $pricing->express_merchant_funding }}',
                    settlement: '{{ $pricing->settlement }}',
                    whalut_fee: '{{ $pricing->whalut_fee }}',
                    authorization_fee: '{{ $pricing->authorization_fee }}',
                    capture_settlement_fee: '{{ $pricing->capture_settlement_fee }}',
                    american_express_auth_fee: '{{ $pricing->american_express_auth_fee }}',
                    pin_based_debit_transaction_fee: '{{ $pricing->pin_based_debit_transaction_fee }}',
                    ebt_transaction_fee: '{{ $pricing->ebt_transaction_fee }}',
                    batch_fee: '{{ $pricing->batch_fee }}',
                    ivr: '{{ $pricing->ivr }}',
                    voice_authorization: '{{ $pricing->voice_authorization }}',
                    monthly_statement_fee: '{{ $pricing->monthly_statement_fee }}',
                    monthly_account_fee: '{{ $pricing->monthly_account_fee }}',
                    monthly_help_desk_fee: '{{ $pricing->monthly_help_desk_fee }}',
                    monthly_compass_fee: '{{ $pricing->monthly_compass_fee }}',
                    monthly_data_guardian_fee: '{{ $pricing->monthly_data_guardian_fee }}',
                    non_complete_pci_fee: '{{ $pricing->non_complete_pci_fee }}',
                    end_billing_option_fee: '{{ $pricing->end_billing_option_fee }}',
                    quest_virtual_terminal: '{{ $pricing->quest_virtual_terminal }}',
                    paylink: '{{ $pricing->paylink }}',
                    setup_fee: '{{ $pricing->setup_fee }}',
                    update_fee: '{{ $pricing->update_fee }}',
                    annual_regulatory_reporting_fee: '{{ $pricing->annual_regulatory_reporting_fee }}',
                    chargback_fee: '{{ $pricing->chargback_fee }}',
                    retrieval_fee: '{{ $pricing->retrieval_fee }}',
                    keyed_application_fee: '{{ $pricing->keyed_application_fee }}',
                    inactivity_fee: '{{ $pricing->inactivity_fee }}',
                    discount_billed_to_merchant: '{{ $pricing->discount_billed_to_merchant }}',
                    helpdesk_calls_for_non_supported_terminals: '{{ $pricing->helpdesk_calls_for_non_supported_terminals }}',
                    voyager_capture: '{{ $pricing->voyager_capture }}',
                    wright_express: '{{ $pricing->wright_express }}',
                    monthly_wireless_access_fee: '{{ $pricing->monthly_wireless_access_fee }}',
                    express_merchant_funding_fee: '{{ $pricing->express_merchant_funding_fee }}',
                    host_capture_fees: '{{ $pricing->host_capture_fees }}',
                    host_capture_monthly_fee: '{{ $pricing->host_capture_monthly_fee }}',
                    host_capture_transaction_fee: '{{ $pricing->host_capture_transaction_fee }}',
                    host_capture_administrative_transaction_fee: '{{ $pricing->host_capture_administrative_transaction_fee }}',



                }),
                    mounted: function () {

                }
                });
            </script>
    @endpush
@endsection