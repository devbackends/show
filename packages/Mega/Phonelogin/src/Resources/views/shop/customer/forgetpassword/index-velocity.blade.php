@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.customer.forgot-password.page_title') }}
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
    <div class="container box-container box-container__with-secondary-action">

        <div class="heading">
            <h4>
                {{ __('velocity::app.customer.forget-password.forgot-password')}}
            </h4>
        </div>

        <div class="custom-form-container">
            <form method="post" action="{{ route('customer.forgot-password.store') }}" @submit.prevent="onSubmit">

                {{ csrf_field() }}

                {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                <div class="control-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                    <?php
                    $helper = app('Mega\Phonelogin\Helper\PhoneloginHelper');
                    ?>
                    @if($helper->isEnabled())
                    <label for="email" class="mandatory label-style">
                        {{ __('megaPhoneLogin::app.customer.forgot-password-form.email-phone') }}
                    </label>

                    <input type="text" name="email" class="form-style" placeholder="{{ __('megaPhoneLogin::app.customer.forgot-password-form.email-phone') }}" v-validate="'required'" />
                    @else
                    <label for="email" class="mandatory label-style">
                        {{ __('shop::app.customer.forgot-password.email') }}
                    </label>

                    <input type="email" name="email" class="form-style" placeholder="  {{ __('shop::app.customer.forgot-password.email') }}" v-validate="'required|email'" />
                    @endif


                    <span class="control-error" v-if="errors.has('email')">
                        @{{ errors.first('email') }}
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}
                <div class="submit-container">
                    <button id="custom-submit-button" class="btn btn-primary" type="submit">
                        {{ __('shop::app.customer.forgot-password.submit') }}
                    </button>
                </div>

            </form>

            {{--{!! view_render_event('bagisto.shop.customers.forget_password.after') !!}--}}
        </div>

    </div>
    <div class="box-container__secondary-action">
        <p>Remember your password?</p>
        <a href="{{ route('customer.session.index') }}" class="btn btn-outline-primary box-container__secondary-action-button">
            {{ __('velocity::app.customer.signup-form.login') }}
        </a>
    </div>
</div>
@endsection