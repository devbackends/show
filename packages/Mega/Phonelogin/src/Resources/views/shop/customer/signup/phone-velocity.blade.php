<div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
    <label for="last_name" class="required label-style">
        {{ __('megaPhoneLogin::app.customer.signup-form.phone') }}
    </label>
    @if(isset($customer))
        <input
            type="text"
            id="megaphone"
            class="form-style"
            name="phone"
            v-validate="'required'"
            value="{{ $customer->phone }}"
            @change="showModal('megaOtpValidation')"
            data-vv-as="{{ __('megaPhoneLogin::app.customer.signup-form.phone') }}" />
    @else
        <input
            type="text"
            id="megaphone"
            class="form-style"
            name="phone"
            v-validate="'required'"
            value="{{ old('megaphone') }}"
            @change="showModal('megaOtpValidation')"
            data-vv-as="{{ __('megaPhoneLogin::app.customer.signup-form.phone') }}" />
    @endif
    <span class="control-error" v-if="errors.has('phone')">
        @{{ errors.first('phone') }}
    </span>
</div>



<input type="hidden" value="{{route('mega.phonelogin.sendOtp')}}" name="megaOtpUrl" id="megaOtpUrl" />
<input type="hidden" value="{{route('mega.phonelogin.verifyOtp')}}" name="megaOtpVUrl" id="megaOtpVurl" />
<input type="hidden" name="megavcode" id="megavcode">

@push('css')
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/mega/phonelogin/assets/css/intlTelInput.min.css')}}">
    <link rel="stylesheet" href="{{ bagisto_asset('vendor/mega/phonelogin/assets/css/phonelogin.css')}}">
@endpush
@push('scripts')
    <script src="{{ bagisto_asset('vendor/mega/phonelogin/assets/js/intlTelInput-jquery.js')}}"></script>
    <script src="{{ bagisto_asset('vendor/mega/phonelogin/assets/js/utils.js')}}"></script>
    <script src="{{ bagisto_asset('vendor/mega/phonelogin/assets/js/plogin.js')}}"></script>
@endpush
<div id="megaOtpContainer" style="display:none">
    <modal id="megaOtpValidation" :is-open="modalIds.megaOtpValidation">
        <h3 slot="header">{{ __('megaPhoneLogin::app.customer.signup-form.popup.title') }}</h3>
        <i class="rango-close"></i>
        <div slot="body">
            <p>{{ __('megaPhoneLogin::app.customer.signup-form.popup.sub-title') }}</p>
            <div class="control-group" style="padding-bottom:20px">
                <label for="megaphone" class="required">{{ __('megaPhoneLogin::app.customer.signup-form.popup.otp-label') }}</label>
                <input type="text" id="megaotp" class="form-style" name="megaotp" v-validate="" />
                <span class="control-error" id="vcode-error" style="display:none">{{ __('megaPhoneLogin::app.customer.signup-form.popup.otp-error') }}.</span>
                <span class="control-error" id="vresp-error" style="display:none"></span>
            </div>
            <div class="control-group">
                <button type="button" id="megaverify" class="theme-btn " >{{ __('megaPhoneLogin::app.customer.signup-form.popup.verify-otp') }}</button>
                <button type="button" id="megaresend" class="theme-btn" >{{ __('megaPhoneLogin::app.customer.signup-form.popup.resend-otp') }}</button>
                <button type="button" id="hidePopup" class="theme-btn">{{ __('megaPhoneLogin::app.customer.signup-form.popup.close') }}</button>
            </div>
        </div>
    </modal>
</div>