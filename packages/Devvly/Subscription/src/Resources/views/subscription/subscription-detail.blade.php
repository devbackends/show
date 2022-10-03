@extends('admin::layouts.content')

@section('page_title')
    {{ __('subscription::app.subscription.subscribe') }}
@stop

@section('content-wrapper')
    @if(!empty($pricing))
        @php $pricing=$pricing[0] @endphp
        @php $monthly_subscription= $pricing->whalut_fee + $pricing->monthly_data_guardian_fee + $pricing->monthly_account_fee  @endphp
    <div class="page-title-nav">
        <p class="paragraph bold black padding-15 no-margin">Subscription detail</p>
    </div>
    <div class="pricing-detail-container">
        <div class="subscription-pricing-detail h-overflow padding-tb-30">
            <div class="control-group-third">
                <h1 class="heading info-dark align-center no-margin"> ${{$monthly_subscription}} </h1>
                <h3 class="heading align-center no-margin"> Monthly subscription </h3>
            </div>
            <div class="control-group-third">
                <h1 class="heading info-dark align-center no-margin"> {{$pricing->flat_rate}}% </h1>
                <h3 class="heading align-center no-margin"> Credit card processing </h3>
            </div>
            <div class="control-group-third">
                <h1 class="heading info-dark align-center no-margin"> ${{$pricing->authorization_fee}} </h1>
                <h3 class="heading align-center no-margin"> Transaction fee </h3>
            </div>
        </div>

        <div class="pricing-fees-container">
                <div class="width-100 h-overflow  padding-tb-15"  >
                    <h2 class="heading bold align-center">Pricing Detail</h2>
                </div>


                <div class="width-100 h-overflow padding-20 " style="border-top:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;">
                    <span class="paragraph-big bold black no-margin ">Monthly subscription</span>
                    <a class="bordered-button light pricing-detail-button"><span>See detail</span></a>
                    <span class="paragraph-big bold black no-margin f-right ">{{$monthly_subscription}}$</span>
                </div>


                <form id="pricing_form">
                    <div class="form-container centralize_div_88 margin-t-30">
                        @csrf

                        <div class="padding-tb-15">
                            <div class="centralize_div_60 full_width">

                                <span class="paragraph-big bold black no-margin  padding-20">Card processing and transaction fee</span>
                                <div class="padding-tb-15">

                                    <table class="pricing-table ">
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Residency Fee</p></td>
                                            <td class="second_column"><p>${{$pricing->monthly_account_fee}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">2AGUN Fee</p></td>
                                            <td class="second_column"><p>${{$pricing->whalut_fee}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Data Gardian Fee</p>
                                            </td>
                                            <td class="second_column"><p>${{$pricing->monthly_data_guardian_fee}}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Flat Rate Credit Card
                                                    Fee</p></td>
                                            <td class="second_column"><p>{{$pricing->flat_rate}}%</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Authorization Fee</p>
                                            </td>
                                            <td class="second_column"><p>${{$pricing->authorization_fee}} per
                                                    transaction</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">IVR/DialPay
                                                    Authorization</p>
                                                <p class="table-description-text">Only if used</p></td>
                                            <td class="second_column"><p>${{$pricing->ivr}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Voice Authorization</p>
                                                <p class="table-description-text">very rarely used</p></td>
                                            <td class="second_column"><p>${{$pricing->voice_authorization}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Non Complete PCI
                                                    Questionnaire Fee</p>
                                                <p class="table-description-text">Only if non-compliant</p></td>
                                            <td class="second_column"><p>${{$pricing->non_complete_pci_fee}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Chargeback Fee</p>
                                                <p class="table-description-text">Only if chargeback occurs</p></td>
                                            <td class="second_column"><p>${{$pricing->chargback_fee}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Retrieval Fee</p>
                                                <p class="table-description-text">Only if customers bank requests copy
                                                    of sales draft to Substantiate transactio</p></td>
                                            <td class="second_column"><p>${{$pricing->retrieval_fee}}</p></td>
                                        </tr>
                                        <tr>
                                            <td class="first_column"><p class="table-main-text">Express Merchant Funding
                                                    Fee</p></td>
                                            <td class="second_column"><p>{{$pricing->express_merchant_funding}}%</p>
                                            </td>
                                        </tr>
                                    </table>

                                </div>

                            </div>
                        </div>

                        <div class="align-center">

                            <a class="bordered-button light"><span>Unsubscribe</span></a>
                        </div>
                    </div>
                </form>


        </div>
    </div>
    @endif
@stop

@push('scripts')

@endpush
