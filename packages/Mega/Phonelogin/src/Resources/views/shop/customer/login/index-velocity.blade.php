@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
@push('scripts')
<style>
    body {
        background-color: #F2F2F2;
    }
</style>
@endpush
<div class="auth-content form-container">

    {!! view_render_event('bagisto.shop.customers.login.before') !!}

    <div class="container box-container box-container__with-secondary-action">

        <div class="heading">
            <h4>
                {{ __('velocity::app.customer.login-form.customer-login')}}
            </h4>

        </div>

        <div class="custom-form-container">
            <form method="POST" action="{{ route('customer.session.create') }}" @submit.prevent="onSubmit">

                {{ csrf_field() }}

                {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                    <?php
                    $helper = app('Mega\Phonelogin\Helper\PhoneloginHelper');
                    ?>
                    @if($helper->isEnabled())
                    <label for="email" class="mandatory label-style">
                        {{ __('megaPhoneLogin::app.customer.login-form.email-phone') }}
                    </label>
                    <input type="text" class="form-style" name="email" v-validate="'required'" value="{{ old('email') }}" placeholder="{{ __('megaPhoneLogin::app.customer.login-form.email-phone') }}" data-vv-as="&quot;{{ __('megaPhoneLogin::app.customer.login-form.email-phone-err') }}&quot;" />
                    @else
                    <label for="email" class="mandatory label-style form-labels">
                        {{ __('shop::app.customer.login-form.email') }}
                    </label>
                    <input type="text" class="form-style " name="email" v-validate="'required|email'" value="{{ old('email') }}" placeholder="{{ __('shop::app.customer.login-form.email') }}" data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;" />
                    @endif
                    <span class="control-error" v-if="errors.has('email')">
                        @{{ errors.first('email') }}
                    </span>
                </div>

                <div class="form-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="mandatory label-style">
                        {{ __('shop::app.customer.login-form.password') }}
                    </label>

                    <input type="password" class="form-style" name="password" v-validate="'required'" value="{{ old('password') }}" placeholder="{{ __('shop::app.customer.login-form.password') }}" data-vv-as="&quot;{{ __('shop::app.customer.login-form.password') }}&quot;" />

                    <span class="control-error" v-if="errors.has('password')">
                        @{{ errors.first('password') }}
                    </span>


                    <div class="mt10">
                        @if (Cookie::has('enable-resend'))
                        @if (Cookie::get('enable-resend') == true)
                        <a href="{{ route('customer.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                        @endif
                        @endif
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                <div class="submit-container">
                    <input id="custom-submit-button" class="btn btn-primary" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">

                    <a href="{{ route('customer.forgot-password.create') }}" class="custom-forgot-link">
                        {{ __('shop::app.customer.login-form.forgot_pass') }}
                    </a>

                </div>

            </form>
        </div>

    </div>

    <div class="box-container__secondary-action">
        <p>Or</p>
        <a href="{{ route('customer.register.index') }}" class="btn btn-outline-primary box-container__secondary-action-button">
            {{ __('velocity::app.customer.login-form.sign-up')}}
        </a>
    </div>
    {{--{!! view_render_event('bagisto.shop.customers.login.after') !!}--}}
</div>
@endsection