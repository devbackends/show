@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="container my-5">
        {!! view_render_event('bagisto.shop.customers.login.before') !!}
{{--        <div class="row justify-content-center mb-4">
            <div class="col-md-8 col-lg-4">
                <div class="row align-items-center">
                    <div class="col-auto pr-0"><i class="far fa-exclamation-triangle fa-2x text-gray"></i></div>
                    <div class="col"><p class="mb-0"><strong>2AGunShow is currently in Beta.</strong> If you identify any issues please contact us and we will work to resolve the issue promptly.</p></div>
                </div>
            </div>
        </div>--}}
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-4 box-section-wrapper">
                <div class="box-section mb-4">
                    <div class="box-section__head heading">
                        <p class="box-section__head-title">{{ __('velocity::app.customer.login-form.customer-login')}}</p>
                    </div>
                    <div class="custom-form-container">
                        <form method="POST"
                              action="{{ route('customer.session.create') }}"
                              @submit.prevent="onSubmit">
                            {{ csrf_field() }}
                            {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}
                            <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email"
                                       class="mandatory label-style form-labels">{{ __('shop::app.customer.login-form.email') }}</label>
                                <input type="email" name="email"
                                       class="form-control" v-validate="'required|email'"
                                       value="{{ old('email') }}"
                                       placeholder="Email"
                                       data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('email')">
                                    @{{ errors.first('email') }}
                                </span>
                            </div>
                            <div class="form-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password"
                                       class="mandatory label-style">{{ __('shop::app.customer.login-form.password') }}</label>
                                <input type="password" class="form-control" name="password"
                                       v-validate="'required'"
                                       value="{{ old('password') }}"
                                       placeholder="Password"
                                       data-vv-as="&quot;{{ __('shop::app.customer.login-form.password') }}&quot;">
                                <span class="control-error" v-if="errors.has('password')">
                                    @{{ errors.first('password') }}
                                </span>
                            </div>
                            <div class="submit-container box-section__action">
                                <input id="custom-submit-button" type="submit"
                                       value="{{ __('shop::app.customer.login-form.button_title') }}"
                                       class="btn btn-primary">
                                <a href="{{ route('customer.forgot-password.create') }}"
                                   class="btn btn-link">{{ __('shop::app.customer.login-form.forgot_pass') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center">
                    <p>New to 2AGunShow?</p>
                    <a href="{{ route('customer.register.index') }}" class="btn btn-outline-primary">Create an
                        account</a>
                </div>
            </div>
        </div>
    </div>
@endsection
