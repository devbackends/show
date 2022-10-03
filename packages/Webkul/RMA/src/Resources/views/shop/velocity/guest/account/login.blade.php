@extends('shop::layouts.master')

@section('page_title')
    {{ __('rma::app.shop.guest-users.title') }}
@endsection

@section('content-wrapper')
    @push('scripts')
        <style>
            body {  background-color: #F2F2F2;  }
        </style>
    @endpush
    <div class="auth-content form-container">
        <div class="container box-container">


                <div class="heading">
                    <h4>
                        {{ __('rma::app.shop.guest-users.heading') }}
                    </h4>
                </div>

                <div class="custom-form-container">
                    <form
                        method="POST"
                        @submit.prevent="onSubmit"
                        action="{{ route('rma.guest.logincreate') }}">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                        <div class="form-group margin_b_20" :class="[errors.has('order_id') ? 'has-error' : '']">
                            <label for="email" class="mandatory label-style">
                                {{ __('rma::app.shop.guest-users.order-id') }}
                            </label>

                            <input
                                type="text"
                                class="form-style"
                                name="order_id"
                                value="{{ old('order_id') }}"
                                v-validate="'required|integer'"
                                placeholder="{{ __('rma::app.shop.guest-users.order-id') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;" />

                            <span class="control-error" v-if="errors.has('order_id')">
                                @{{ errors.first('order_id') }}
                            </span>
                        </div>

                        <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="password" class="mandatory label-style">
                                {{ __('rma::app.shop.guest-users.email') }}
                            </label>

                            <input
                                type="email"
                                class="form-style"
                                name="email"
                                v-validate="'required'"
                                value="{{ old('email') }}"
                                placeholder="{{ __('rma::app.shop.guest-users.email') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;" />

                            <span class="control-error" v-if="errors.has('email')">
                                @{{ errors.first('email') }}
                            </span>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                        <div  class="submit-container">
                            <input id="custom-submit-button" class="theme-btn" type="submit" value="{{ __('rma::app.shop.guest-users.button-text') }}" />
                        </div>

                    </form>
                </div>

        </div>
    </div>
@endsection
