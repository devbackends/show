@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.customer.forgot-password.page_title') }}
@endsection

@section('content-wrapper')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-6 box-section-wrapper">
            <div class="box-section mb-4">
                <div class="box-section__head heading">
                    <p class="box-section__head-title">{{ __('velocity::app.customer.forget-password.forgot-password')}}</p>
                    <p>
                        {{ __('velocity::app.customer.forget-password.recover-password-text')}}
                    </p>
                </div>
                <div class="custom-form-container">
                    <form method="post" action="{{ route('customer.forgot-password.store') }}" @submit.prevent="onSubmit">
                        <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="mandatory label-style form-labels">{{ __('shop::app.customer.forgot-password.email') }}</label>
                            <input type="email" name="email" class="form-control" v-validate="'required|email'" />

                            <span class="control-error" v-if="errors.has('email')">
                                @{{ errors.first('email') }}
                            </span>
                        </div>
                        <div class="submit-container box-section__action">

                            <button class="btn btn-primary" type="submit">
                                {{ __('shop::app.customer.forgot-password.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center">
                <p>Remember your password?</p>
                <a href="{{ route('customer.session.index') }}" class="btn btn-outline-primary">{{ __('velocity::app.customer.signup-form.login') }}</a>
            </div>
        </div>
    </div>
</div>


@endsection