

    <sign-up-form></sign-up-form>

        <script type="text/x-template" id="sign-up-form-template">
            <div>
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
                <div class="row justify-content-center">
                    <div class="col box-section-wrapper">
                        <div class="box-section mb-4">
                            <div class="box-section__head heading">
                                <h3>Create an account</h3>
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

                                    <seller-controls></seller-controls>

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
                                        <input id="custom-submit-button" type="submit" :disabled="!termsAndCondsChecked" value="{{ __('shop::app.customer.signup-form.title') }}" class="btn btn-primary">
                                    </div>
                                    <!-- <small class="form-text text-muted">Please read and accept the terms and conditions in order to sign up.</small> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>
        <script type="text/x-template" id="seller-controls-template">
            <div class="seller-form-controls">
                <div class="form-group">
                    <label class="w-100">{{ __('marketplace::app.shop.sellers.account.signup.want-to-be-seller') }}</label>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="yes" name="want_to_be_seller" value="1" v-model="want_to_be_seller" :checked="want_to_be_seller" class="custom-control-input">
                        <label class="custom-control-label" for="yes">{{ __('marketplace::app.shop.sellers.account.signup.yes') }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="no" name="want_to_be_seller" value="0" v-model="want_to_be_seller" class="custom-control-input">
                        <label class="custom-control-label" for="no">{{ __('marketplace::app.shop.sellers.account.signup.no') }}</label>
                    </div>
                </div>

                <div class="form-group" :class="[errors.has('url') ? 'has-error' : '']" v-show="want_to_be_seller == 1">
                    <label for="url" class="required">{{ __('marketplace::app.shop.sellers.account.signup.shop_url') }}</label><i class="fas fa-question-circle text-info ml-2" data-toggle="tooltip" data-placement="top" title="Create your custom URL for your 2A Gun Show Domain."></i>

                    <input type="text" class="form-control form-style" name="url" v-validate="'required'" value="{{ old('url') }}" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.signup.shop_url') }}&quot;" v-on:keyup="checkShopUrl($event.target.value)">

                    <span class="control-help">Please use lowercase letters and numbers only.</span>

                    <span class="control-info text-success" v-if="isShopUrlAvailable != null && isShopUrlAvailable">{{ __('marketplace::app.shop.sellers.account.signup.shop_url_available') }}</span>

                    <span class="control-info text-danger" v-if="isShopUrlAvailable != null && !isShopUrlAvailable">{{ __('marketplace::app.shop.sellers.account.signup.shop_url_not_available') }}</span>

                    <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                </div>
            </div>
        </script>
        <script>
            Vue.component('sign-up-form', {
                template: "#sign-up-form-template",

                data: () => ({
                    termsAndCondsChecked: false,
                }),

                methods: {
                    acceptModalBtnClicked() {
                        this.termsAndCondsChecked = true;
                    }
                },
            });

            Vue.component('seller-controls', {
                template: "#seller-controls-template",

                data: () => ({
                    want_to_be_seller: 1,

                    isShopUrlAvailable: null
                }),

                watch: {
                    'want_to_be_seller': function(newVal, oldVal) {
                        this.toggleButtonDisable(newVal)
                        if (this.want_to_be_seller == '1') {
                            $('[data-toggle="tooltip"]').tooltip();
                        }

                    }
                },

                methods: {
                    checkShopUrl(shopUrl) {
                        this_this = this;

                        this.$http.post("{{ route('marketplace.seller.url') }}", {
                            'url': shopUrl
                        })
                            .then(function(response) {
                                if (response.data.available) {
                                    this_this.isShopUrlAvailable = true;

                                    document.querySelectorAll("form button.btn")[0].disabled = false;
                                } else {
                                    this_this.isShopUrlAvailable = false;
                                    document.querySelectorAll("form button.btn")[0].disabled = true;
                                }
                            })
                            .catch(function(error) {
                                document.querySelectorAll("form button.btn")[0].disabled = true;
                            })
                    },

                    toggleButtonDisable(value) {
                        var buttons = document.querySelectorAll("form button.btn");

                        if (value == 1) {
                            buttons[0].disabled = true;
                        } else {
                            buttons[0].disabled = false;
                        }
                    },
                },
            });
        </script>

