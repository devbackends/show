@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.checkout.onepage.title') }}
@stop

@section('content-wrapper')
<div class="container-fluid checkout__navbar">
    <div class="container h-100">
        <div class="row d-flex align-items-center h-100">
            <div class="col-auto">
                <logo-component></logo-component>
            </div>
            <div class="col text-right">
                <div class="checkout__navbar-contact">
                    <span class="icon whatsapp-icon"></span>
                    <p class="checkout__navbar-contact-text">(312) 202 7909</p>
                </div>
                <div class="checkout__navbar-contact">
                    <span class="icon letter-icon"></span>
                    <p class="checkout__navbar-contact-text">service@2agunshow.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container checkout__wrapper">
    <div id="checkout">
        <checkout :checkout-success-url="'{{route('shop.checkout.success')}}'"></checkout>
    </div>
</div>
@endsection

@push('scripts')
@include('shop::checkout.cart.coupon')
<script src={{env('CLEARENT_API_URL') . '/js-sdk/js/clearent-host.js'}}></script>
<script src="{{asset('themes/velocity/assets/js/checkout.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#top").css('display', 'none');
        $("#sticky-header").css('display', 'none');
        $("#category-menu-header").css('display', 'none');
        $('.address-container')
    });
</script>
@endpush