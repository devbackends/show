@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.forgot-password.page_title') }}
@stop

@push('css')
    <style>
        .button-group {
            margin-bottom: 25px;
        }
        .primary-back-icon {
            vertical-align: middle;
        }
    </style>
@endpush

@section('content-wrapper')
   packages\Mega\Phonelogin\src\Resources\views\shop\customer\forgetpassword\index-default.blade.php
    <div class="auth-content">

        {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

        <form method="post" action="{{ route('customer.forgot-password.store') }}" @submit.prevent="onSubmit">

            {{ csrf_field() }}

            <div class="login-form">

                <div class="login-text">{{ __('shop::app.customer.forgot-password.title') }}</div>

                {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email">{{ __('megaPhoneLogin::app.customer.forgot-password-form.email-phone') }}</label>
                    <input type="text" class="control" name="email" v-validate="'required'" data-vv-as="&quot;{{ __('megaPhoneLogin::app.customer.forgot-password-form.email-phone-err') }}&quot;">
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                <div class="button-group">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('shop::app.customer.forgot-password.submit') }}
                    </button>
                </div>

                <div class="control-group" style="margin-bottom: 0px;">
                    <a href="{{ route('customer.session.index') }}">
                        <i class="icon primary-back-icon"></i>
                        {{ __('shop::app.customer.reset-password.back-link-title') }}
                    </a>
                </div>

            </div>
        </form>
        {{--{!! view_render_event('bagisto.shop.customers.forget_password.after') !!}--}}
    </div>
@endsection