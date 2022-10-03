@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection
@push('css')
    <style type="text/css">
        .account-head {
            height: 50px;
        }
        .remove-icon {
            right: 15px;
            font-size: 22px;
            height: 24px;
            text-align: center;
            position: absolute;
            border-radius: 50%;
            color: #333;
            width: 24px;
            padding: 0px;
            top: 10px;
        }
        .remove-icon:before {
            content: "x";
        }
    </style>
@endpush
@section('page-detail-wrapper')
    <div class="row  no-margin">
        <div class="col-md-12">
            <div class="account-head">

                <h1 class="paragraph-big bold">
                    {{ __('shop::app.customer.account.profile.index.title') }}
                </h1>
            </div>
        </div>
    </div>


    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

    <form   id="profile-form"
            method="POST"
            @submit.prevent="onSubmit"
            class="account-table-content"
            action="{{ route('customer.profile.edit') }}">

        @csrf

        <div class="row padding-tb-10 no-margin">
            <div class="col-md-3"><p class="paragraph no-margin">
                <div :class="`${errors.has('first_name') ? 'has-error' : ''}`">
                    <label class="mandatory paragraph regular-font">
                        {{ __('shop::app.customer.account.profile.fname') }}
                    </label>

                    <div>
                        <input value="{{ $customer->first_name }}" name="first_name" type="text" class="paragraph" v-validate="'required'"/>
                        <span class="control-error"
                              v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="">
                    <label class="paragraph regular-font">
                        {{ __('shop::app.customer.account.profile.lname') }}
                    </label>

                    <div class="">
                        <input value="{{ $customer->last_name }}" name="last_name" type="text" class="paragraph"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row padding-tb-10 no-margin">
            <div class="col-md-3">
                <div :class="`${errors.has('gender') ? 'has-error' : ''}`">
                    <label class="mandatory paragraph regular-font">
                        {{ __('shop::app.customer.account.profile.gender') }}
                    </label>

                    <div class="">
                        <div class="radio-input-container">
                            <div class="" style="float: left">
                                <span class="custom-radio-box">
                                    <input type="radio" name="gender" value="Male" v-validate="'required'" @if ($customer->gender == "Male") checked="checked" @endif data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">
                                    <label for="custom-radio-view" class="custom-radio-view red"></label>
                                </span>
                            </div>
                            <div class="radio-text-container">
                                <span class="paragraph">Male</span>
                            </div>
                        </div>
                        <div class="radio-input-container">
                            <div class="" style="float: left">
                                <span class="custom-radio-box">
                                    <input type="radio" name="gender" value="Female" v-validate="'required'" @if ($customer->gender == "Female") checked="checked" @endif data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">
                                    <label for="custom-radio-view" class="custom-radio-view red"></label>
                                </span>
                            </div>
                            <div class="radio-text-container">
                                <span class="paragraph">Female</span>
                            </div>
                        </div>

                        {{--
                        <select
                                name="gender"
                                v-validate="'required'"
                                class="control styled-select"
                                data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">

                            <option value="" @if ($customer->gender == "") selected @endif></option>
                            <option
                                    value="Other"
                                    @if ($customer->gender == "Other")
                                    selected="selected"
                                    @endif>
                                {{ __('velocity::app.shop.gender.other') }}
                            </option>

                            <option
                                    value="Male"
                                    @if ($customer->gender == "Male")
                                    selected="selected"
                                    @endif>
                                {{ __('velocity::app.shop.gender.male') }}
                            </option>

                            <option
                                    value="Female"
                                    @if ($customer->gender == "Female")
                                    selected="selected"
                                    @endif>
                                {{ __('velocity::app.shop.gender.female') }}
                            </option>
                        </select>
--}}

                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div :class="`${errors.has('date_of_birth') ? 'has-error' : ''}`">
                    <label class="paragraph regular-font">
                        {{ __('shop::app.customer.account.profile.dob') }}
                    </label>

                    <div class="">
                        <input  class="paragraph"
                                type="date"
                                name="date_of_birth"
                                placeholder="dd/mm/yyyy"
                                value="{{ old('date_of_birth') ?? $customer->date_of_birth }}"
                                v-validate=""
                                data-vv-as="&quot;{{ __('shop::app.customer.account.profile.dob') }}&quot;"/>

                        <span class="control-error" v-if="errors.has('date_of_birth')">
                                @{{ errors.first('date_of_birth') }}
                            </span>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="row">
                    <label class="mandatory paragraph regular-font">
                        {{ __('shop::app.customer.account.profile.email') }}
                    </label>

                    <div class="">
                        <input value="{{ $customer->email }}" name="email" type="text" v-validate="'required'" class="paragraph" />
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row padding-tb-10 no-margin">
            <div class="col-md-3">
                <div class="">
                    <label class="paragraph regular-font">
                        {{ __('velocity::app.shop.general.enter-current-password') }}
                    </label>

                    <div :class="`${errors.has('oldpassword') ? 'has-error' : ''}`">
                        <input value="" name="oldpassword" type="password" class="paragraph"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="">
                    <label class="paragraph regular-font">
                        {{ __('velocity::app.shop.general.new-password') }}
                    </label>

                    <div :class="`${errors.has('password') ? 'has-error' : ''}`">
                        <input  class="paragraph"
                                value=""
                                name="password"
                                type="password"
                                v-validate="'min:6|max:18'"/>

                        <span class="control-error" v-if="errors.has('password')">
                            @{{ errors.first('password') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <label class="paragraph regular-font">
                        {{ __('velocity::app.shop.general.confirm-new-password') }}
                    </label>

                    <div :class="`${errors.has('password_confirmation') ? 'has-error' : ''}`">
                        <input value="" name="password_confirmation" type="password" class="paragraph"
                               v-validate="'min:6|confirmed:password'" data-vv-as="confirm password"/>

                        <span class="control-error" v-if="errors.has('password_confirmation')">
                            @{{ errors.first('password_confirmation') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row padding-tb-30 no-margin">
            <button id="profile-submit-button"
                    type="submit"
                    class="theme-btn mb20 hide">
                {{ __('velocity::app.shop.general.update') }}
            </button>
            <div class="col-md-12">
                <div class="col-md-6 no-padding">
                    <a @click="document.getElementById('profile-submit-button').click();"  style="cursor: pointer;" class="light-black-bordered-button">
                        <span><i class="far fa-save"></i></span> <span>Save</span>
                    </a>

                </div>
                <div class="col-md-6 no-padding">
                    <a @click="showModal('deleteProfile')" style="cursor: pointer;" class="light-black-bordered-button open-modal-button">
                        <span><i class="far fa-trash-alt"></i></span> <span>Delete account</span>
                    </a>
                </div>
            </div>



        </div>
    </form>

    <form class="profile-modal" method="POST" action="{{ route('customer.profile.destroy') }}" @submit.prevent="onSubmit">
        @csrf

        <modal id="deleteProfile" :is-open="modalIds.deleteProfile">
            <div slot="header" class="align-center">
                <div class="padding-15"><i class="far fa-3x fa-trash-alt"></i></div>
                <div class="padding-15">
                    <h4 class="heading">
                        Are you sure you want to delete your account?
                        Please confirm by entering your password
                    </h4>
                </div>
            </div>

            {{--<i class="rango-close"></i>--}}
            <div slot="body">
                <div class="control-group align-center" :class="[errors.has('password1') ? 'has-error' : '']">
                    <input type="password"  class="control modal-input" id="password" placeholder="Password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('password1')">@{{ errors.first('password1') }}</span>
                </div>

                <div class="page-action padding-tb-30 modal-buttons-container" >
                    <a style="cursor: pointer;" class="light-black-bordered-button" id="cancel-button" >
                        <span></span> <span>Cancel</span>
                    </a>
                    <button id="delete-button" type="submit"  class="theme-btn mb20">
                        confirm
                    </button>

                </div>
            </div>
        </modal>

    </form>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}

    <script>
        $( document ).ready(function() {

            $(document).on('click','#cancel-button',function(e){
                $('.profile-modal').addClass('hide');
            });
            $(document).on('click','.open-modal-button',function(e){
                $('.profile-modal').removeClass('hide');
            });
        });
    </script>

@endsection