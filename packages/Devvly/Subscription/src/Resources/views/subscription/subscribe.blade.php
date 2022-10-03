@extends('admin::layouts.content')

@section('page_title')
    {{ __('subscription::app.subscription.subscribe') }}
@stop

@section('content-wrapper')

    <form id="subscription_form" method="POST" action="{{ route('subscription.store_card') }}"  @submit.prevent="onSubmit">
        <div class="content padding-25">
            <div class="padding-tb-60 width-100">
                <div class="first-step-title-container">
                    <span class="p-36 align-center">The first step in setting up your store is setting up your <strong>2A Gunshow subscription</strong></span>
                </div>
            </div>
            <div class="width-100">
                <h1 class="heading info-dark align-center no-margin poster-font"> $39 </h1>
                <h3 class="heading align-center no-margin"> Monthly subscription </h3>
            </div>
            <div class="padding-tb-30 width-100">
                <div class="first-step-card-container">
                    @csrf()
                  <div class="control-group" :class="[errors.has('plan') ? 'has-error' : '']">
                    <select class="control" v-validate="'required'" id="plan" name="plan" data-vv-as="&quot;{{ __('subscription::app.subscription.select_a_plan') }}&quot;">
                      <option value="">{{ __('subscription::app.subscription.select_a_plan') }}</option>
                      @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request()->input('plan') == $plan->frequency ? 'selected' : '' }}>
                          {{ __('subscription::app.subscription.' . $plan->frequency) }}
                        </option>
                      @endforeach

                    </select>
                    <span class="control-error" v-if="errors.has('plan')">@{{ errors.first('plan') }}</span>
                  </div>
                  <div class="control-group" id="coupon_container" :class="[errors.has('coupon_code') ? 'has-error' : '']">
                    <input minlength="4" maxlength="6" class="control" type="text"  name="coupon_code" id="coupon_code" data-vv-as="&quot;{{ __('subscription::app.subscription.coupon_code') }}&quot;" placeholder="{{ __('subscription::app.subscription.coupon_code') }}">
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
            <div class="padding-30 width-100 terms-container">
                <div class="subscription-terms-container padding-30" >
                    <p class="paragraph bold black padding-5">Terms and conditions</p>
                    <p class="paragraph black padding-5">
                        ipsum dolor sit amet, consectetur adipiscing elit. Morbi porttitor, tortor vestibulum vulputate accumsan, est diam vestibulum purus, nec auctor sapien neque non neque. Sed ornare convallis risus ac ullamcorper. Maecenas at maximus sapien. Nulla non ipsum eget enim hendrerit fermentum. Vivamus faucibus risus ut tellus ullamcorper fermentum. Mauris sagittis sem nibh, et aliquam nibh efficitur id. Sed fringilla cursus neque, nec commodo est pretium non. Nulla facilisi. Morbi a ex in nulla maximus porttitor et varius sapien. Cras hendrerit mi eu ante vulputate placerat. In vitae eleifend massa. Sed tristique, justo eu eleifend tristique, ipsum justo finibus ipsum, vitae egestas dolor metus sed ex. Fusce hendrerit massa at lobortis accumsan. Curabitur finibus dolor vel consequat pharetra. Proin id aliquet massa, quis ultricies velit. Vivamus ultricies dolor et metus suscipit, nec sollicitudin dolor ornare. Maecenas tincidunt urna at massa congue, a vestibulum ante laoreet. Curabitur vehicula placerat lacus, id interdum erat malesuada vitae. Duis auctor luctus velit, non volutpat sapien aliquet sed. Nunc volutpat placerat volutpat. Donec eget enim et sapien ultrices tempor. Integer ut mattis ante, at scelerisque ex. Donec tempor purus est, sit amet rhoncus leo iaculis quis. Sed dapibus orci ex, id auctor lectus sagittis ut. Proin vitae ligula tristique, blandit nunc at, viverra erat. Vivamus lacinia erat quam, sed finibus eros dictum sit amet. Nulla mollis viverra est, in rutrum quam maximus et. Nullam viverra felis in varius accumsan. Phasellus tempus leo eu eros sollicitudin, in tincidunt libero consectetur. Pellentesque quis hendrerit leo, nec rhoncus velit. Donec nunc nulla, maximus ac vestibulum quis, pellentesque accumsan risus. Nunc ultrices ex ac libero posuere, vitae varius mi imperdiet. Cras venenatis mi eget dui feugiat, nec tincidunt nibh vestibulum. In hac habitasse platea dictumst. Nullam vel quam facilisis, posuere mauris venenatis, dapibus lorem. Nunc id purus auctor, volutpat ligula nec, iaculis augue. Ut id turpis ac libero auctor consequat. Mauris mauris velit, hendrerit nec urna at, tempus commodo erat. Vestibulum bibendum, quam quis aliquam laoreet, mi neque rutrum augue, in condimentum tortor massa at mauris. Duis suscipit magna eget felis porttitor finibus.
                    </p>
                </div>
            </div>
            <div class="padding-30 width-100 alert-container h-overflow">
                <div class="first-step-alert-container">
                    <div class="alert-container" :class="[errors.has('zip-code') ? 'has-error' : '']">
                        <div class="subscription-alert-icon" >
                            <span class="icon custom-info-icon" ></span>
                        </div>
                        <div class="subscription-alert-text" class="paragraph black">
                            Your monthly subscription is separate from your credit card processing and transaction fees. You will set up your card processing in the next step.
                        </div>
                    </div>
                </div>
            </div>
            <div class="padding-tb-30 width-100 agree-container h-overflow">
                <div class="first-step-submission">
                    <div class="checkbox">
                    <span class="checkbox no-margin custom-check-box">
                        <input type="checkbox" name="agreement" id="10" value="10" required :class="[errors.has('agreement') ? 'has-error' : '']">
                        <label for="custom-checkbox-view " class="custom-checkbox-view dblock"></label>
                        <span class="control-error" v-if="errors.has('agreement')">@{{ errors.first('agreement') }}</span>
                    </span>
                        <span class="paragraph regular-font  subscription-alert-info">I read and agree with the terms and conditions</span>
                    </div>

                </div>
            </div>
            <div class="padding-tb-30 width-100">
                <div class="first-step-submission">
                    <button type="submit" id="submit_btn" class="btn btn-lg btn-primary">
                        Click here to subscribe
                    </button>
                </div>
            </div>
        </div>
    </form>

@stop

@push('scripts')

    <script src={{env('CLEARENT_API_URL') . '/js-sdk/js/clearent-host.js'}}></script>
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
            includeClearent(settings);

          $( document ).on('blur','#coupon_code',function() {
            var submit_btn = $(settings.submit_button);
            if($("#coupon_code").val()){
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
              $.ajax({
                url: "{{route('subscription.check-coupon')}}",
                method: "POST",
                dataType: "json",
                data: {"coupon_code":$("#coupon_code").val()},
                success: function success(data) {
                  if(data['status']==false){
                    $("#coupon_container").addClass('has-error');
                    $("#coupon_status").html(data['message']);
                    submit_btn.attr("disabled", true);
                  }else{
                    $("#coupon_container").removeClass('has-error');
                    $("#coupon_status").html(' ');
                    submit_btn.attr("disabled", false);
                  }
                }
              });
            }else{
              $("#coupon_container").removeClass('has-error');
              $("#coupon_status").html(' ');
              submit_btn.attr("disabled", false);
            }

          });
        });
    </script>
@endpush
