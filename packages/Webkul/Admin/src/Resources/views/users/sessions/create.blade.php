@extends('saas::companies.layouts.master')

@section('page_title')
    {{ __('admin::app.users.sessions.title') }}
@stop

@section('content-wrapper')
    @push('scripts')
    <style>
        body {  background-color: #F2F2F2;  }
    </style>
    @endpush
    <div class="company-content" style="background-color: #F2F2F2;">
        <div class="form-container signin_container" >

            <h1 class="padding_10">{{ __('admin::app.users.sessions.title') }}</h1>

            <form method="POST" action="{{ route('admin.session.store') }}" @submit.prevent="onSubmit">
                @csrf

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="custom_label">{{ __('admin::app.users.sessions.email') }}</label>
                    <input type="text" v-validate="'required|email'" class="control full_width no_margin" id="email"
                           name="email" data-vv-as="&quot;{{ __('admin::app.users.sessions.email') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="custom_label">{{ __('admin::app.users.sessions.password') }}</label>
                    <input type="password" v-validate="'required|min:6'" class="control full_width no_margin"
                           id="password" name="password"
                           data-vv-as="&quot;{{ __('admin::app.users.sessions.password') }}&quot;" value=""/>
                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>
                <div style="padding-top:15px;">
                    <div class="forgot_password_container" >
                        <a class="custom_forgot_password" href="{{ route('admin.forget-password.create') }}">{{ __('admin::app.users.sessions.forget-password-link-title') }}</a>
                    </div>

                    <div class="signin_button_container" >
                        <button class="btn btn-xl btn-primary"
                                style="">{{ __('admin::app.users.sessions.submit-btn-title') }}</button>
                    </div>
                </div>

            </form>


        </div>

    </div>

@stop