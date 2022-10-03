@extends('saas::companies.layouts.master')

@section('head')
    <meta name="title" content="2AGUNSHOW"/>
    <meta name="description" content="Gun Shows"/>
    <meta name="keywords" content="Gun Shows"/>
    @push('scripts')
        <link rel="stylesheet" href="{{ asset('css/gun.css') }}">
    @endpush
@endsection

@section('content-wrapper')

    @if( count($pricing) > 0)

        @php $pricing=$pricing[0] @endphp

        @php $monthly_subscription= $pricing->whalut_fee + $pricing->monthly_data_guardian_fee + $pricing->monthly_account_fee  @endphp
    <div class="top-container" style="max-height:294px;">
        <div class="logo-container">
            <span class="icon gunshow-logo-icon"></span>
        </div>

        <img src="{{asset('images/shooter.png')}}" style="width: 100%;max-height:287px;">
        <div class="top-title" style="top:40%;">
            <h1 class="heading uppercase white poster">our pricing details</h1>
        </div>

        <div class="triangle-container">
            <span class="icon triangle-logo-icon"></span>
        </div>
    </div>
    <div class="padding-15 ">
        <div class="go-back-container"><a href="{{route('saas.home.index')}}"><span class="padding-sides-5 gray-dark"><i class="far fa-1x fa-chevron-left"></i></span><span class="paragraph bold gray-dark padding-sides-5">Go back</span></a></div>
        <div class="subscription-pricing-detail h-overflow padding-tb-30 padding-sides-45">
            <div class="control-group-third ">
                <h1 class="heading info-dark align-center no-margin"> $ {{ $monthly_subscription }} </h1>
                <h3 class="heading align-center no-margin"> Monthly subscription </h3>
            </div>
            <div class="control-group-third">
                <h1 class="heading info-dark align-center no-margin"> {{$pricing->flat_rate}}% </h1>
                <h3 class="heading align-center no-margin"> Credit card processing </h3>
            </div>
            <div class="control-group-third">
                <h1 class="heading info-dark align-center no-margin"> ${{number_format((float)$pricing->authorization_fee, 2, '.', '') }} </h1>
                <h3 class="heading align-center no-margin"> Transaction fee </h3>
            </div>
        </div>
    </div>
    <div class="sell-button-container" style="margin-bottom: 6vw">
        <a href="https://www.2agunshow.com/marketplace/start-selling"> {{--{{route('company.create.store')}}--}}
            <button class="btn btn-primary custom_button">Sign up and start selling NOW!</button>
        </a>
    </div>
    <div class="pricing-fees-container gray-lighter-bg" >
        <div class="width-100 h-overflow  padding-tb-15">
            <h2 class="heading bold align-center poster">Pricing Detail</h2>
        </div>
        <div class="width-100 h-overflow padding-20 white-bg">
            <span class="paragraph-big bold black no-margin ">Monthly subscription</span>
            <a class="bordered-button light pricing-detail-button" id="see-details-button">
                <span>See detail</span>
            </a>
            <a class="bordered-button light pricing-detail-button info-dark-border dnone"  id="hide-details-button">
                <span class="info-dark">Hide detail</span>
            </a>
            <span class="paragraph-big bold black no-margin f-right ">{{$monthly_subscription}}$</span>
        </div>
        <div class="width-100 h-overflow padding-20 white-bg monthly-subscription-details dnone">
            <span class="paragraph no-margin ">Residency Fee</span>
            <span class="paragraph no-margin f-right ">${{$pricing->monthly_account_fee}}</span>
        </div>
        <div class="width-100 h-overflow padding-20 light-gray-bg  monthly-subscription-details dnone">
            <span class="paragraph no-margin ">2A Gun Show Fee</span>
            <span class="paragraph no-margin f-right ">${{$pricing->whalut_fee}}</span>
        </div>
        <div class="width-100 h-overflow padding-20 white-bg  monthly-subscription-details dnone">
            <span class="paragraph no-margin ">Data Guardian Fee</span>
            <span class="paragraph no-margin f-right ">${{$pricing->monthly_data_guardian_fee}}</span>
        </div>
        <div class="width-100 h-overflow padding-t-20  monthly-subscription-details dnone">
            <span class="paragraph no-margin ">Please note: the Residency Fee and Data Guardian Fee will be billed through your clearent account. The 2aGunShow Fee will be billed to your credit caerd on file.</span>
        </div>
        <form id="pricing_form">
            <div class="form-container centralize_div_88 margin-t-30">

                <div class="padding-tb-15">
                    <div class="centralize_div_60 full_width">
                        <span class="paragraph-big bold black no-margin  padding-20">Card processing and transaction fee</span>
                        <div class="width-100 h-overflow padding-20 white-bg margin-t-20">
                            <span class="paragraph  black no-margin ">Flat Rate Credit Card Fee</span>

                            <span class="paragraph no-margin f-right ">{{$pricing->flat_rate}}%</span>
                        </div>
                        <div class="width-100 h-overflow padding-20 light-gray-bg">
                            <span class="paragraph black no-margin ">Authorization Fee</span>

                            <span class="paragraphno-margin f-right ">${{ number_format((float)$pricing->authorization_fee, 2, '.', '') }} per transaction</span>
                        </div>

                        <div class="padding-tb-15">
                            <div class="paragraph-big bold black no-margin  padding-sides-20">
                                  <p class="paragraph-big bold black">Miscellaneous fees</p>
                                  <p class="paragraph">(Rarely Incurred)</p>
                            </div>
                            <table class="pricing-table "><tbody>


                                <tr>
                                    <td class="first_column">
                                        <p class="table-main-text">IVR/DialPay Authorization</p>
                                        <p class="table-description-text">Only if used</p>
                                    </td>
                                    <td class="second_column">
                                        <p>${{ number_format((float) $pricing->ivr, 2, '.', '') }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="first_column">
                                        <p class="table-main-text">Voice Authorization</p>
                                        <p class="table-description-text">very rarely used</p>
                                    </td>
                                    <td class="second_column">
                                        <p>${{$pricing->voice_authorization}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="first_column">
                                        <p class="table-main-text">Non Complete PCI Questionnaire Fee</p>
                                        <p class="table-description-text">Only if non-compliant</p>
                                    </td>
                                    <td class="second_column">
                                        <p>${{$pricing->non_complete_pci_fee}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="first_column">
                                        <p class="table-main-text">Chargeback Fee</p>
                                        <p class="table-description-text">Only if chargeback occurs</p>
                                    </td>
                                    <td class="second_column">
                                        <p>${{$pricing->chargback_fee}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="first_column">
                                        <p class="table-main-text">Retrieval Fee</p>
                                        <p class="table-description-text">Only if customers bank requests copy of sales draft to Substantiate transactio</p>
                                    </td>
                                    <td class="second_column">
                                        <p>${{$pricing->retrieval_fee}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="first_column">
                                        <p class="table-main-text">Express Merchant Funding Fee</p>
                                    </td>
                                    <td class="second_column">
                                        <p>{{$pricing->express_merchant_funding}}%</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $( document ).ready(function() {
                $(document).on('click','#see-details-button',function(e){
                    $(".monthly-subscription-details").removeClass('dnone');
                    $("#see-details-button").addClass('dnone');
                    $("#hide-details-button").removeClass('dnone');
                });
                $(document).on('click','#hide-details-button',function(e){
                    $(".monthly-subscription-details").addClass('dnone');
                    $("#see-details-button").removeClass('dnone');
                    $("#hide-details-button").addClass('dnone');
                });
            });
        </script>
@endif
@endsection

