@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.onepage.title') }}
@stop

@section('head')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content-wrapper')

    <div class="checkout__navbar">
        @if(isset($_GET['message']))
            <div style="position: absolute;top:20px;    right:20px;z-index: 10;" class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="far fa-exclamation-triangle pr-3"></i>
                <strong>Payment Error!</strong> {{$_GET['message']}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="container h-100">
            <div class="row h-100">
                <div class="col">
                    <a href="{{route('shop.home.index')}}">
                        <img src="/images/gunshow-logo.svg" alt="2AGunShow">
                    </a>
                </div>
                <div class="col-auto checkout__navbar-links">
                    <a href="call:3122027909" class="h3">
                        <i class="fab fa-whatsapp"></i>
                        <span>(312) 202 7909</span>
                    </a>
                    <a href="mailto:service@2agunshow.com" class="h3">
                        <i class="far fa-envelope"></i>
                        <span>service@2agunshow.com</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <checkout ref="checkout"
        :checkout-success-url="'{{route('shop.checkout.success')}}'"
        :cart="{{$json_cart}}"
        :seller="{{$seller}}"
        :customer="{{$customer}}"
        :terms="{{ json_encode($terms)}}">
    </checkout>
    <form  id="paymentForm" method="POST" action="" style="height:0px;">
        <input type="hidden" name="dataValue" id="dataValue" />
        <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
        <button type="button" id="authorizePay" style="visibility: hidden;height:0px;"
                class="btn btn-outline-dark btn-sm mt-1 AcceptUI"
                data-billingAddressOptions='{"show":true, "required":false}'
                data-apiLoginID="2s7Jf9TjvNZ"
                data-clientKey="4awHu322AzhyPkJKJ743Arw3YgV7K4N6S8MJzrEADuwzsX6uKD4JKq45698cWq8A"
                data-acceptUIFormBtnTxt="Submit"
                data-acceptUIFormHeaderTxt="Card Information"
                data-responseHandler="authorizeResponseHandler">Add a new card
        </button>
    </form>
@endsection

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script src="{{config('services.2acommerce.gateway_url') . '/tokenizer/tokenizer.js'}}"></script>
    <script src="{{asset('themes/market/assets/js/checkout.js')}}" type="module"></script>


    <script type="text/javascript" src="https://jstest.authorize.net/v3/AcceptUI.js" charset="utf-8"></script>

    <script>
        $('.collapse').collapse();

        function authorizeResponseHandler(response) {
            if (response.messages.resultCode === "Error") {
                var i = 0;
                while (i < response.messages.message.length) {
                    window.showAlert('alert-danger', 'Error', response.messages.message[i].text);
                    i = i + 1;
                }
            } else {
                app.__vue__.$refs.checkout.$refs.Payment.$refs.AuthorizePayment[0].responseHandler(response);
            }
        }
    </script>
@endpush