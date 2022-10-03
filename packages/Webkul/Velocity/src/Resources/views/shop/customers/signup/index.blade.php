@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.customer.signup-form.page-title') }}
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
                {{ __('velocity::app.customer.signup-form.user-registration')}}
            </h4>
        </div>

        <div class="custom-form-container">


            {!! view_render_event('bagisto.shop.customers.signup.before') !!}

            <form method="post" action="{{ route('customer.register.create') }}" @submit.prevent="onSubmit">

                {{ csrf_field() }}

                {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                <div class="control-group margin_b_20" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <label for="first_name" class="required label-style">
                        {{ __('shop::app.customer.signup-form.firstname') }}
                    </label>

                    <input type="text" class="form-style" name="first_name" v-validate="'required'" value="{{ old('first_name') }}" placeholder=" {{ __('shop::app.customer.signup-form.firstname') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;" />

                    <span class="control-error" v-if="errors.has('first_name')">
                        @{{ errors.first('first_name') }}
                    </span>
                </div>

                <div class="control-group margin_b_20" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <label for="last_name" class="required label-style">
                        {{ __('shop::app.customer.signup-form.lastname') }}
                    </label>

                    <input type="text" class="form-style" name="last_name" v-validate="'required'" value="{{ old('last_name') }}" placeholder="{{ __('shop::app.customer.signup-form.lastname') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;" />

                    <span class="control-error" v-if="errors.has('last_name')">
                        @{{ errors.first('last_name') }}
                    </span>
                </div>

                <div class="control-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required label-style">
                        {{ __('shop::app.customer.signup-form.email') }}
                    </label>

                    <input type="email" class="form-style" name="email" v-validate="'required|email'" value="{{ old('email') }}" placeholder="{{ __('shop::app.customer.signup-form.email') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;" />

                    <span class="control-error" v-if="errors.has('email')">
                        @{{ errors.first('email') }}
                    </span>
                </div>

                <div class="control-group margin_b_20" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required label-style">
                        {{ __('shop::app.customer.signup-form.password') }}
                    </label>

                    <input type="password" class="form-style" name="password" v-validate="'required|min:6'" ref="password" value="{{ old('password') }}" placeholder="{{ __('shop::app.customer.signup-form.password') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.password') }}&quot;" />

                    <span class="control-error" v-if="errors.has('password')">
                        @{{ errors.first('password') }}
                    </span>
                </div>

                <div class="control-group margin_b_20" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                    <label for="password_confirmation" class="required label-style">
                        {{ __('shop::app.customer.signup-form.confirm_pass') }}
                    </label>

                    <input type="password" class="form-style" name="password_confirmation" v-validate="'required|min:6|confirmed:password'" placeholder="{{ __('shop::app.customer.signup-form.confirm_pass') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.confirm_pass') }}&quot;" />

                    <span class="control-error" v-if="errors.has('password_confirmation')">
                        @{{ errors.first('password_confirmation') }}
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}
                <div class="submit-container">
                    <button id="custom-submit-button" class="btn btn-primary" type="submit">
                        {{ __('shop::app.customer.signup-form.title') }}
                    </button>
                </div>

            </form>

            {!! view_render_event('bagisto.shop.customers.signup.after') !!}
        </div>
    </div>
    <div class="box-container__secondary-action">
        <p>Or</p>
        <a href="{{ route('customer.session.index') }}" class="btn btn-outline-primary box-container__secondary-action-button">
            {{ __('velocity::app.customer.signup-form.login')}}
        </a>
    </div>
</div>
@endsection