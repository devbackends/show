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

    <sign-up-form></sign-up-form>

    @push('scripts')
        <script type="text/x-template" id="sign-up-form-template">
            <div class="container my-5">
                <div class="modal fade" id="termsAndConditionsModal" tabindex="-1" aria-labelledby="termsAndConditionsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-header-content">
                                    <h5 class="modal-title" id="termsAndConditionsModalLabel">Terms And Conditions</h5>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @include('shop::terms-and-conditions')
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="acceptModalBtnClicked">I accept the terms and conditions</button>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="row justify-content-center mb-4">
                    <div class="col-md-9 col-lg-6">
                        <div class="row align-items-center">
                            <div class="col-auto pr-0"><i class="far fa-exclamation-triangle fa-2x text-gray"></i></div>
                            <div class="col"><p class="mb-0"><strong>2AGunShow is currently in Beta.</strong> If you identify any issues please contact us and we will work to resolve the issue promptly.</p></div>
                        </div>
                    </div>
                </div>--}}
                <div class="row justify-content-center">
                    <div class="col-md-9 col-lg-6 box-section-wrapper">
                        <div class="box-section mb-4">
                            <div class="box-section__head heading">
                                <p class="box-section__head-title">Create An Account</p>
                            </div>
                            <div class="custom-form-container">
                                {!! view_render_event('bagisto.shop.customers.signup.before') !!}
                                <form method="post" action="{{ route('customer.register.create') }}" @submit.prevent="onSubmit">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group margin_b_20" :class="[errors.has('first_name') ? 'has-error' : '']">
                                                <label for="first_name" class="mandatory label-style form-labels">{{ __('shop::app.customer.signup-form.firstname') }}</label>
                                                <input type="text" name="first_name" placeholder="{{ __('shop::app.customer.signup-form.firstname') }}" class="form-control" v-validate="'required'" value="{{ old('first_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;">
                                                <span class="control-error" v-if="errors.has('first_name')">
                                                    @{{ errors.first('first_name') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group margin_b_20" :class="[errors.has('last_name') ? 'has-error' : '']">
                                                <label for="last_name" class="mandatory label-style form-labels">{{ __('shop::app.customer.signup-form.lastname') }}</label>
                                                <input type="text" name="last_name" class="form-control" v-validate="'required'" value="{{ old('last_name') }}" placeholder="{{ __('shop::app.customer.signup-form.lastname') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;">
                                                <span class="control-error" v-if="errors.has('last_name')">
                                                    @{{ errors.first('last_name') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                                        <label for="email" class="mandatory label-style form-labels">{{ __('shop::app.customer.signup-form.email') }}</label>
                                        <input type="email" class="form-control" name="email" v-validate="'required|email'" value="{{ old('email') }}" placeholder="{{ __('shop::app.customer.signup-form.email') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;">
                                        <span class="control-error" v-if="errors.has('email')">
                                            @{{ errors.first('email') }}
                                        </span>
                                    </div>
                                    <div class="form-group" :class="[errors.has('password') ? 'has-error' : '']">
                                        <label for="password" class="mandatory label-style">{{ __('shop::app.customer.signup-form.password') }}</label>
                                        <input type="password" class="form-control" name="password" v-validate="'required|min:6'" ref="password" value="{{ old('password') }}" placeholder="{{ __('shop::app.customer.signup-form.password') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.password') }}&quot;">
                                        <span class="control-error" v-if="errors.has('password')">
                                            @{{ errors.first('password') }}
                                        </span>
                                    </div>
                                    <div class="form-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                                        <label for="password-re" class="mandatory label-style">{{ __('shop::app.customer.signup-form.confirm_pass') }}</label>
                                        <input type="password" class="form-control" name="password_confirmation" v-validate="'required|min:6|confirmed:password'" placeholder="{{ __('shop::app.customer.signup-form.confirm_pass') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.confirm_pass') }}&quot;">
                                        <span class="control-error" v-if="errors.has('password_confirmation')">
                                            @{{ errors.first('password_confirmation') }}
                                        </span>
                                    </div>

                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="want_to_be_seller" value="1" class="custom-control-input">
                                            <label class="custom-control-label" for="yes">Are you a <strong>seller</strong> who would like to list products for sale on the site?</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="want_to_be_trainer" value="1" class="custom-control-input">
                                            <label class="custom-control-label" for="no">Are you an <strong>instructor</strong> and would like to list your classes for registration?</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input name="terms_and_conditions" required v-model="termsAndCondsChecked" type="checkbox" class="custom-control-input" id="termsAndConditions">
                                            <label class="custom-control-label" for="termsAndConditions">I have read and accept the <a href="#" data-toggle="modal" data-target="#termsAndConditionsModal"> terms and conditions.</a></label>
                                        </div>
                                        <span class="control-error" v-if="errors.has('terms_and_conditions')">
                                            Please read and accept the terms and conditions.
                                        </span>
                                    </div>

                                    <div class="submit-container box-section__action">
                                        <submit-button id="custom-submit-button" :disabled="!termsAndCondsChecked" text="{{ __('shop::app.customer.signup-form.title') }}" :loading="submitLoading"></submit-button>
                                    </div>
                                    <!-- <small class="form-text text-muted">Please read and accept the terms and conditions in order to sign up.</small> -->
                                </form>
                            </div>
                        </div>
                        <div class="text-center">
                            <p>Do you already have an account?</p>
                            <a href="{{ route('customer.session.index') }}" class="btn btn-outline-primary"> {{ __('velocity::app.customer.signup-form.login')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </script>
        <script>
            Vue.component('sign-up-form', {
                template: "#sign-up-form-template",
                inject: ['$validator'],
                data: () => ({
                    termsAndCondsChecked: false,
                    submitLoading: false
                }),

                methods: {
                    acceptModalBtnClicked() {
                        this.termsAndCondsChecked = true;
                    }
                },
            });
        </script>

    @endpush


@endsection
