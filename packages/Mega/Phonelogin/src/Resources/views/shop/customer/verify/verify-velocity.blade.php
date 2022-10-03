@extends('shop::layouts.master')

@section('page_title')
    {{ __('megaPhoneLogin::app.customer.verify-phone.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">
        <div class="container box-container">
            <div class="col-lg-10 col-md-12 offset-lg-1">

                <div class="heading">
                    <h2 class="fs24 fw6">
                        {{ __('megaPhoneLogin::app.customer.verify-phone.page-title') }}
                    </h2>
                </div>
                <div class="body col-12">
                    <div  class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="megaphone" class="required">{{ __('megaPhoneLogin::app.customer.signup-form.phone') }}</label>
                        <input type="text" id="megaphone" class="control form-style" name="phone" value="{{ $customer->phone }}" data-vv-as="{{ __('megaPhoneLogin::app.customer.signup-form.phone') }}">
                        <div>
                            <span><a href="javascript:void(0)" id="megaSendcode"> {{ __("megaPhoneLogin::app.customer.verify-phone.send-otp") }}</a></span>
                        </div>
                        <span class="control-error" v-if="errors.has('phone')">{{ __('megaPhoneLogin::app.customer.signup-form.invalid-phone') }}</span>
                    </div>
                    <input type="hidden" value="{{route('mega.phonelogin.sendOtp')}}" name="megaOtpUrl" id="megaOtpUrl" />
                    <input type="hidden" value="{{route('mega.phonelogin.verifyOtp')}}" name="megaOtpVUrl" id="megaOtpVurl" />
                    <input type="hidden" value="{{route('customer.profile.index')}}" name="successUrl" id="successUrl" />
                    <div  class="control-group">
                        <label for="megaotp" class="required">{{ __('megaPhoneLogin::app.customer.signup-form.popup.otp-label') }}</label>
                        <input type="text" id="megaotp" class="control form-style" name="verificationcode" data-vv-as="{{ __('megaPhoneLogin::app.customer.signup-form.popup.otp-label') }}">
                        <span class="control-error" id="vcode-error">{{ __('megaPhoneLogin::app.customer.signup-form.popup.otp-error') }}</span>
                    </div>
                    <div  class="control-group">
                        <button type="button" id="megaverify" class="theme-btn" >{{ __('megaPhoneLogin::app.customer.signup-form.popup.verify-otp') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/mega/phonelogin/assets/css/intlTelInput.min.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/mega/phonelogin/assets/css/phonelogin.css')}}">
@endpush
@push('scripts')
    <script src="{{ bagisto_asset('vendor/mega/phonelogin/assets/js/intlTelInput-jquery.js')}}"></script>
    <script src="{{ bagisto_asset('vendor/mega/phonelogin/assets/js/utils.js')}}"></script>
    <script src="{{ bagisto_asset('vendor/mega/phonelogin/assets/js/pverify.js')}}"></script>
@endpush
