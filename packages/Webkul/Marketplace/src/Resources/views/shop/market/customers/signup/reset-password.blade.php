@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.customer.reset-password.title') }}
@endsection

@section('content-wrapper')
<div class="container">

<div class="row justify-content-center">
        <div class="col-md-9 col-lg-6 box-section-wrapper">
            <div class="box-section">
                <div class="box-section__head heading">
                    <h3>{{ __('shop::app.customer.reset-password.title')}}</h3>
                </div>
                <div class="custom-form-container">

                <form method="POST" @submit.prevent="onSubmit" action="{{ route('customer.reset-password.store') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group margin_b_20" :class="`form-group ${errors.has('email') ? 'has-error' : ''}`">
                            <label for="email" class="mandatory label-style form-labels">{{ __('shop::app.customer.reset-password.email') }}</label>
                            <input id="email" type="text" name="email" class="form-control" value="{{ old('email') }}" v-validate="'required|email'" />

                            <span class="control-error" v-if="errors.has('email')">
                                @{{ errors.first('email') }}
                            </span>
                        </div>
                        <div class="form-group margin_b_20" :class="`form-group ${errors.has('password') ? 'has-error' : ''}`">
                            <label for="email" class="mandatory label-style form-labels">{{ __('shop::app.customer.reset-password.password') }}</label>
                            <input ref="password" class="form-control" name="password" type="password" v-validate="'required|min:6'" />

                            <span class="control-error" v-if="errors.has('password')">
                                @{{ errors.first('password') }}
                            </span>
                        </div>
                        <div class="form-group margin_b_20" :class="`form-group ${errors.has('confirm_password') ? 'has-error' : ''}`">
                            <label for="confirm_password" class="mandatory label-style form-labels">{{ __('shop::app.customer.reset-password.confirm-password') }}</label>
                            <input type="password" class="form-control" name="password_confirmation" v-validate="'required|min:6|confirmed:password'" />

<span class="control-error" v-if="errors.has('confirm_password')">
    @{{ errors.first('confirm_password') }}
</span>
                        </div>

                        <div class="submit-container box-section__action">
                            <input id="custom-submit-button" type="submit" value="{{ __('shop::app.customer.reset-password.submit-btn-title') }}" class="btn btn-primary">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>


@endsection