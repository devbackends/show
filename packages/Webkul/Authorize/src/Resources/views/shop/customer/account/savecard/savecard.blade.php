@extends('shop::layouts.master')

@section('page_title')
    {{ __('authorize::app.customer.account.savecard.index.page-title') }}
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
    @if(core()->getConfigData('sales.paymentmethods.authorize.debug') == '1')
    <script type="text/javascript" src="{{asset('vendor/webkul/authorize/assets/js/AcceptUITest.js')}}" charset="utf-8"></script>
    @else
    <script type="text/javascript" src="{{asset('vendor/webkul/authorize/assets/js/AcceptUI.js')}}" charset="utf-8"></script>
    @endif

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
            <span class="account-heading">{{ __('authorize::app.customer.account.savecard.index.title') }}</span>

            <span class="account-action">
                <a id="add-new-card" style="cursor: pointer;">{{ __('authorize::app.customer.account.savecard.index.add') }}</a>
            </span>

            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">
            <div class="table" style="margin-top:10px;">
                    <table >
                        <thead style="text-align: center;">
                            <tr>
                                <th>{{ __('authorize::app.customer.account.savecard.index.isdefault') }}</th>

                                <th>{{ __('authorize::app.customer.account.savecard.index.id') }}</th>

                                <th>{{ __('authorize::app.customer.account.savecard.index.card-number') }}</th>

                                <th>{{ __('authorize::app.customer.account.savecard.index.action') }}</th>

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
<script>
    $(document).on("click","#add-new-card",function() {
        $("#authorizePay").trigger('click');
    });

    $(document).on("click",".isdefault",function(){
        id = this.id;
        $.ajax({
            type: "GET",
            url: "{{route('authorize.account.make.card.default')}}",
            data: {id:this.id},
            success: function( response ) {
                if (response.success == 'true') {
                    console.log('updated');
                } else {
                    console.log('not updated !');
                }
            }
        });
    });

    $(document).on("click",'.delete',function(){
        var result = confirm("Are you sure want to delete this card ?");
        if (result) {
            var row = "#"+'row'+this.id;
            $.ajax({
                type: "GET",
                url: "{{route('authorize.delete.saved.cart')}}",
                data: {id:this.id},
                success: function( response ) {
                    if (response == '1') {
                        $(row).css('display', 'none');
                    }
                }
            });
        }
    });

    function responseHandler(response) {
        if (response.messages.resultCode === "Error") {
            var i = 0;
            while (i < response.messages.message.length) {
                alert(response.messages.message[i].text);
                console.log(
                    response.messages.message[i].code + ": " +
                    response.messages.message[i].text
                );
                i = i + 1;
            }
        } else {
            paymentFormUpdate(response);
        }
    }

    function paymentFormUpdate(response) {

        document.getElementById("dataDescriptor").value = response.opaqueData.dataDescriptor;
        document.getElementById("dataValue").value = response.opaqueData.dataValue;

        _token = "{{csrf_token()}}";

        $.ajax({
            type: "POST",
            url: "{{route('authorize.account.store.card')}}",
            data: {_token:_token,response:response},
            success: function( response ) {
                if (response.cardExist != 'true') {
                    var $tr = $('<tr id="row'+response.id+'">');
                    $tr.append($('<td/>').html('<span class="radio"><input type="radio" id="'+response.id+'" name="radio" class="isdefault"><label class="radio-view" for="'+response.id+'"></label> </span>'));
                    $tr.append($('<td/>').html(response.id));
                    $tr.append($('<td/>').html(response.last_four));
                    $tr.append($('<td/>').html('<span class="delete icon trash-icon" id='+response.id+' style="cursor:pointer;"></span>'));
                    $('.list-order tr:first').before($tr);
                } else {
                    alert('Card already exist !');
                }
            }
        });
    }

</script>
@endpush
