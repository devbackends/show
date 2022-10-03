@extends('shop::layouts.master')

@section('page_title')
    {{ __('clearent::app.customer.account.savecard.index.page-title') }}
@endsection

@section('content-wrapper')

@php
if (core()->getConfigData('sales.paymentmethods.authorize.debug') == '1') {
    $merchantLoginId = core()->getConfigData('sales.paymentmethods.authorize.test_api_login_ID');
    $merchantAuthentication = core()->getConfigData('sales.paymentmethods.authorize.test_transaction_key');
} else {
    $merchantLoginId = core()->getConfigData('sales.paymentmethods.authorize.api_login_ID');
    $merchantAuthentication = core()->getConfigData('sales.paymentmethods.authorize.transaction_key');
}
@endphp

<div class="account-content">
    @include('shop::customers.account.partials.sidemenu')

    <form id="paymentForm" method="POST" action="">
        <input type="hidden" name="dataValue" id="dataValue" />
        <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
        <button type="button" id="authorizePay" style="display:none"
            class="AcceptUI"
            data-billingAddressOptions='{"show":true, "required":false}'
            data-apiLoginID="{{$merchantLoginId}}"
            data-clientKey="{{core()->getConfigData('sales.paymentmethods.authorize.client_key')}}"
            data-acceptUIFormBtnTxt="Submit"
            data-acceptUIFormHeaderTxt="Card Information"
            data-responseHandler="responseHandler">Pay
        </button>
    </form>

    <div class="account-layout">

        <div class="account-head">
            <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
            <span class="account-heading">{{ __('clearent::app.customer.account.savecard.index.title') }}</span>

            <span class="account-action">
                <a id="add-new-card" style="cursor: pointer;">{{ __('clearent::app.customer.account.savecard.index.add') }}</a>
            </span>

            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">
            <div class="table" style="margin-top:10px;">
                    <table >
                        <thead style="text-align: center;">
                            <tr>
                                <th>{{ __('clearent::app.customer.account.savecard.index.isdefault') }}</th>

                                <th>{{ __('clearent::app.customer.account.savecard.index.id') }}</th>

                                <th>{{ __('clearent::app.customer.account.savecard.index.card-number') }}</th>

                                <th>{{ __('clearent::app.customer.account.savecard.index.action') }}</th>

                            </tr>
                        </thead>

                        <tbody style="text-align:center;" class="list-order">
                            @foreach($cardDetail as $key =>$cardDetails)
                            <tr id="row{{$cardDetails->id}}">
                                <td>
                                    <span class="radio">
                                        <input type="radio" class="isdefault" id="{{$cardDetails->id}}" name="radio" @if($cardDetails->is_default == '1') checked="checked" @endif>
                                        <label class="radio-view" for="{{$cardDetails->id}}"></label>
                                    </span>
                                </td>
                                <td>{{$cardDetails->id}}</td>
                                <td>{{$cardDetails->last_four}}</td>
                                <td><span class="icon trash-icon delete" id="{{$cardDetails->id}}" style="cursor:pointer;"></span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
              </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
@endpush
