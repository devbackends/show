@extends('admin::layouts.content')

@section('page_title')
    {{ __('subscription::app.subscription.subscribe') }}
@stop

@section('content-wrapper')

    <div class="content padding-22">
        <form id="subscription_form" method="POST" action="{{ route('subscription.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i onclick="history.length > 1 ? history.go(-1) : window.location = window.location.hostname + '/admin/dashboard';" class="icon angle-left-icon back-link"></i>
                        Subscription
                    </h1>
                </div>
                <div class="page-action">
                    <button type="submit" id="submit_btn" class="btn btn-lg btn-primary">
                        Subscribe
                    </button>
                </div>
            </div>
            <div class="page-content">
                <div class="form-container">
                    <div class="accordian active">
                        <div class="accordian-content">
                            @csrf()
                            <div class="control-group" :class="[errors.has('plan') ? 'has-error' : '']">
                                <label for="plan" class="required">{{ __('subscription::app.subscription.plan') }}</label>
                                <select class="control" v-validate="'required'" id="plan" name="plan" data-vv-as="&quot;{{ __('subscription::app.subscription.plan') }}&quot;">
                                    <option value="">{{ __('subscription::app.subscription.select_option') }}</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ request()->input('plan') == $plan->frequency ? 'selected' : '' }}>
                                            {{ __('subscription::app.subscription.' . $plan->frequency) }}
                                        </option>
                                    @endforeach

                                </select>
                                <span class="control-error" v-if="errors.has('plan')">@{{ errors.first('plan') }}</span>
                            </div>
                            <div class="control-group" id="coupon_container" :class="[errors.has('coupon_code') ? 'has-error' : '']">
                                <label for="coupon_code">Coupon Code</label>
                                <input minlength="4" maxlength="6" class="control" type="text"  name="coupon_code" id="coupon_code" data-vv-as="&quot;Coupon Code&quot;">
                                <span class="control-error" v-if="errors.has('coupon_code')">@{{ errors.first('coupon_code') }}</span>
                                <span class="control-error" id="coupon_status"></span>
                            </div>
                            <div class="control-group" :class="[errors.has('jwt_token') ? 'has-error' : '']">
                                <input type="hidden" v-validate="'required'" name="jwt_token" id="jwt_token" data-vv-as="&quot;{{ __('subscription::app.subscription.jwt_token') }}&quot;">
                                <span class="control-error" v-if="errors.has('jwt_token')">@{{ errors.first('jwt_token') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('card_type') ? 'has-error' : '']">
                                <input type="hidden" v-validate="'required'" name="card_type" id="card_type" data-vv-as="&quot;{{ __('subscription::app.subscription.card_type') }}&quot;">
                                <span class="control-error" v-if="errors.has('card_type')">@{{ errors.first('card_type') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('last_four') ? 'has-error' : '']">
                                <input type="hidden" v-validate="'required'" name="last_four" id="last_four" data-vv-as="&quot;{{ __('subscription::app.subscription.last_four') }}&quot;">
                                <span class="control-error" v-if="errors.has('last_four')">@{{ errors.first('last_four') }}</span>
                            </div>
                            <div id="payment-form"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')

    <script src="https://gateway-sb.clearent.net/js-sdk/js/clearent-host.js"></script>
    <script src="{{ asset('vendor/devvly/subscription/assets/js/devvly_clearent.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var settings = {
                settings_path: "/admin/subscription/settings",
                form_selector: "form#subscription_form",
                submit_button: "#submit_btn",
                jwt_token_field: 'input[name="jwt_token"]',
                card_type_field: 'input[name="card_type"]',
                last_four_field: 'input[name="last_four"]'
            };
            $( document ).on('blur','#coupon_code',function() {
                if($("#coupon_code").val()){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "check-coupon",
                        method: "POST",
                        dataType: "json",
                        data: {"coupon_code":$("#coupon_code").val()},
                        success: function success(data) {
                            if(data['status']==false){
                                $("#coupon_container").addClass('has-error');
                                $("#coupon_status").html(data['message']);
                            }else{
                                $("#coupon_container").removeClass('has-error');
                                $("#coupon_status").html(' ');
                            }
                        }
                    });
                }else{
                    $("#coupon_container").removeClass('has-error');
                    $("#coupon_status").html(' ');
                }

            });

        });




        includeClearent(settings);
    </script>
@endpush
