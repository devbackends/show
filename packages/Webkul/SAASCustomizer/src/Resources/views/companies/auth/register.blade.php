@extends('saas::companies.layouts.master')

@section('page_title')
    {{ __('saas::app.tenant.registration.merchant-auth') }}
@endsection

@section('head')
    @include('saas::partials.seo')

@endsection

@section('content-wrapper')
    <seller-registration></seller-registration>

    @push('scripts')
        <link href="{{asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet"/>
        <style>body{background-color: #F2F2F2;}</style>

        <script type="text/x-template" id="seller-registration">
            <div class="company-content" style="background-color: #F2F2F2;">
                <div class="form-container registration_container">
                    {{--                    <div class="control-group step-navigator" >
                                            <ul class="step-list">
                                                <li class="step-item"  :class="{ active: isOneActive || isTwoActive || isThreeActive}"  v-on:click="stepNav(1)">1</li>
                                                <li class="step-item-line" :class="{ active: isTwoActive || isThreeActive}" ></li>
                                                <li class="step-item" :class="{ active: isTwoActive || isThreeActive}" v-on:click="stepNav(2)">2</li>
                                                <li class="step-item-line" :class="{ active: isThreeActive }" ></li>
                                                <li class="step-item" :class="{ active: isThreeActive }" v-on:click="stepNav(3)">3</li>
                                            </ul>
                                        </div>--}}
                    <form class="registration" data-vv-scope="step-one" v-if="step_one" @submit.prevent="validateForm('step-one')">
                        {{--          <h3 class="mb-30">{{ __('saas::app.tenant.registration.step-1') }}:</h3>--}}

                        <h4 class="h4_black">{{ __('saas::app.tenant.registration.company-register-statement') }}</h4>

                        <div class="control-group full-width-fields" :class="[errors.has('step-one.email') ? 'has-error' : '']">
                            <label for="email" class="required custom_label">{{ __('saas::app.tenant.registration.email') }}</label>

                            <input type="text" v-validate="'required|email|max:191'" class="control" v-model="email" name="email" data-vv-as="&quot;{{ __('saas::app.tenant.registration.email') }}&quot;" placeholder="Email">

                            <span class="control-error" v-show="errors.has('step-one.email')">@{{ errors.first('step-one.email') }}</span>
                        </div>

                        {{--                        <div class="control-group" :class="[errors.has('step-one.username') ? 'has-error' : '']">
                                                    <label for="username" class="required custom_label">User Name</label>

                                                    <input type="text" class="control" name="username" v-model="username" placeholder="{{ __('saas::app.tenant.registration.username') }}" v-validate="'required|alpha_num'" data-vv-as="&quot;{{ __('saas::app.tenant.registration.username') }}&quot;">

                                                    <span class="control-error" v-show="errors.has('step-one.username')">@{{ errors.first('step-one.username') }}</span>
                                                </div>--}}


                        <div class="control-group full-width-fields" :class="[errors.has('step-one.password') ? 'has-error' : '']">
                            <label for="password" class="required custom_label">{{ __('saas::app.tenant.registration.password') }}</label>

                            <input type="password" name="password" v-validate="'required|min:6'" ref="password" class="control" v-model="password" placeholder="password" data-vv-as="&quot;{{ __('saas::app.tenant.registration.password') }}&quot;">

                            <span class="control-error" v-show="errors.has('step-one.password')">@{{ errors.first('step-one.password') }}</span>
                        </div>

                        <div class="control-group full-width-fields" :class="[errors.has('step-one.password_confirmation') ? 'has-error' : '']">
                            <label for="password_confirmation" class="required custom_label">{{ __('saas::app.tenant.registration.cpassword') }}</label>

                            <input type="password" v-validate="'required|min:6|confirmed:password'" class="control" v-model="password_confirmation" name="password_confirmation" placeholder="Confirm Password" data-vv-as="&quot;{{ __('saas::app.tenant.registration.cpassword') }}&quot;">

                            <span class="control-error" v-show="errors.has('step-one.password_confirmation')">@{{ errors.first('step-one.password_confirmation') }}</span>
                        </div>

                        <div class="control-group">
                            <!-- <input type="submit" class="btn btn-primary" :disabled="errors.has('password') || errors.has('password_confirmation') || errors.has('email')"  value="Continue"> -->
                            <button class="btn btn-primary" :disabled="errors.has('step-one.password') || errors.has('step-one.password_confirmation') || errors.has('step-one.email')">{{ __('saas::app.tenant.registration.continue') }}</button>
                        </div>
                    </form>




                    <form class="registration" @submit.prevent="validateForm('step-two')" data-vv-scope="step-two" v-show="step_two">
                        <div class="step-two">
                            {{--<h3 class="mb-30">{{ __('saas::app.tenant.registration.step-2') }}:</h3>--}}

                            <h4 class="h4_black">{{ __('saas::app.tenant.registration.give-store-name') }}</h4>

                            <div class="control-group" :class="[errors.has('step-two.name') ? 'has-error' : '']">
                                <label for="name" class="required custom_label">{{ __('saas::app.tenant.registration.org-name') }}</label>

                                <input type="text" class="control" id="name" name="name" v-model="name" placeholder="{{ __('saas::app.tenant.registration.org-name') }}" v-validate="'required'" data-vv-as="&quot;{{ __('saas::app.tenant.registration.org-name') }}&quot;">

                                <span class="control-error" v-show="errors.has('step-two.name')">@{{ errors.first('step-two.name') }}</span>
                            </div>
                            <div style="text-align: center;" class="control-group">
                                <h5 class="h5_black bold_font" style="line-height: 46px;"><input ref="store-input" class="control store-input" type="hidden" id="store-input"/><span  id="store-name" class="grey_text hidden">yourstorename</span>.app.2agun.show</h5>
                            </div>
                            <div style="text-align: center;" id="domain-button-container"  class="control-group dnone">
                                <a href="javascript:;" id="domain-button" class="bordered-button"><span>Use a different domain</span></a>
                            </div>
                            <div style="text-align: center;" id="done-button-container" class="control-group dnone">
                                <a href="javascript:;"  id="done-button" class="bordered-button"><span>Done</span></a>
                            </div>

                            <div class="control-group">
                                <h5 class="h5_black">{{ __('saas::app.tenant.registration.store-name-notification') }}.</h5>
                            </div>
                            <div class="control-group">
                                <button class="btn btn-primary" :disabled="errors.has('first_name') || errors.has('last_name') || errors.has('step-two.name')">{{ __('saas::app.tenant.registration.continue') }}</button>
                            </div>
                        </div>
                    </form>

                    <form class="registration" @submit.prevent="validateForm('step-three')" data-vv-scope="step-three" v-show="step_three">
                        @csrf
                        <div class="step-three">
                            {{--<h3 class="mb-30">{{ __('saas::app.tenant.registration.step-3') }}:</h3>--}}

                            <h4 class="h4_black">{{ __('saas::app.tenant.registration.final-step') }}</h4>
                            <div class="control-group" :class="[errors.has('step-three.first_name') ? 'has-error' : '']" >
                                <label for="first_name" class="required custom_label">{{ __('saas::app.tenant.registration.first-name') }}</label>

                                <input type="text" class="control" v-model="first_name" name="first_name" placeholder="Your legal first name" v-validate="'required|alpha_spaces'" data-vv-as="&quot;{{ __('saas::app.tenant.registration.first-name') }}&quot;">

                                <span class="control-error" v-show="errors.has('step-three.first_name')">@{{ errors.first('step-three.first_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('step-three.last_name') ? 'has-error' : '']">
                                <label for="last_name" class="custom_label">{{ __('saas::app.tenant.registration.last-name') }}</label>

                                <input type="text" class="control" name="last_name" v-model="last_name" placeholder="your legal last name" v-validate="'alpha_spaces'" data-vv-as="&quot;{{ __('saas::app.tenant.registration.first-name') }}&quot;">

                                <span class="control-error" v-show="errors.has('step-three.last_name')">@{{ errors.first('step-three.last_name') }}</span>
                            </div>

                            <div id="phone-container"  class="control-group custom-select-field"  :class="[errors.has('step-three.phone_no') ? 'has-error' : '']">
                                <label for="phone_type"
                                       class="required custom_label">{{ __('saas::app.tenant.registration.phone') }}</label>
                                <label   class="select_label phone_type_field">
                                    <select  class="control customSelect" name="phone_type" v-model="phone_type" v-validate="'required'"
                                             data-vv-as="&quot; Phone Type &quot;">
                                        <option :value="null" disabled >Type</option>
                                        <option value="mobile">mobile</option>
                                        <option value="landline">landline</option>
                                    </select>
                                </label>


                                <input  type="text" class="control phone_number_field" name="phone_no" v-model="phone_no" placeholder="Best phone number to reach you" v-validate="'required'" data-vv-as="&quot;{{ __('saas::app.tenant.registration.phone') }}&quot;">
                                <span class="control-error align-error-text no-margin" v-show="errors.has('step-three.phone_type')">@{{ errors.first('step-three.phone_type') }}</span>
                                <span class="control-error align-error-text" v-show="errors.has('step-three.phone_no')">@{{ errors.first('step-three.phone_no') }}</span>
                            </div>

                            <div   class="control-group custom-select-field" :class="[errors.has('step-three.hear_about_us') ? 'has-error' : '']">
                                <label for="hear_about_us" class="required custom_label">{{ __('saas::app.tenant.registration.hear_about_us') }}</label>


                                <label class="select_label">

                                    <select placeholder="fewfwefwefew" class="control customSelect" name="hear_about_us" v-model="hear_about_us"
                                            v-validate="'required'"
                                            data-vv-as="&quot;{{ __('saas::app.tenant.registration.hear') }}&quot;">
                                        <option :value="null" disabled>Where did you hear about us..</option>
                                        <option value="google">Google</option>
                                        <option value="duckduckgo">DuckDuckGo</option>
                                        <option value="youtube">YouTube</option>
                                        <option value="radio">Radio</option>
                                        <option value="print_ad">Print Ad</option>
                                        <option value="social_media">Social Media</option>
                                        <option value="word_of_mouth">Word of Mouth</option>
                                        <option value="gun_show">Gun Show</option>
                                    </select>
                                </label>
                                <span class="control-error" v-show="errors.has('step-three.hear_about_us')">@{{ errors.first('step-three.hear_about_us') }}</span>
                            </div>

                            <div class="control-group " style="height: 60px;">
                                <span class="custom-check-box">
                                <input type="checkbox" value="62" required>
                                <label for="custom-checkbox-view" class="custom-checkbox-view"></label>
                                </span>
                                <span class="agree-notification" >By clicking "Enter my store" you agree to 2AGunShow's <a href="#" class="link_underline">Terms of service</a> and  <a href="#" class="link_underline">privacy policy</a></span>
                            </div>

                            <div class="control-group" >
                                <button class="btn btn-primary" :disabled="errors.has('step-three.username') || errors.has('step-three.name') || createdclicked">Enter my store</button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </script>
        <script>
            $( document ).ready(function() {
                $( document ).on('keyup','#name',function(e){
                    $("#store-name").removeClass('dnone');
                    $("#store-name").html(validateDomain($("#name").val()));
                    $("#store-name").removeClass('grey_text');
                    $("#store-name").addClass('blue_text');
                    $("#store-input").attr('value',validateDomain($("#name").val()));
                    $("#domain-button-container").removeClass('dnone');
                    $("#done-button-container").addClass('dnone');
                    $("#store-input").attr('type','hidden');
                });
                $(document).on('click','#domain-button',function(e){

                    $("#store-name").addClass('dnone');
                    $("#store-input").attr('type','text');
                    $("#domain-button-container").addClass('dnone');
                    $("#done-button-container").removeClass('dnone');
                });
                $(document).on('click','#done-button',function(e){
                    $("#store-name").removeClass('dnone');
                    $("#store-input").attr('type','hidden');
                    $("#domain-button-container").removeClass('dnone');
                    $("#done-button-container").addClass('dnone');
                    $("#store-name").html($("#store-input").val());
                });
                function validateDomain(domain){
                    return domain.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '')
                }
            });
        </script>
        <script>
            Vue.component('seller-registration', {
                template: '#seller-registration',
                inject: ['$validator'],

                data: () => ({
                    data_seed_url: @json(route('company.create.data')),
                    step_one: true,
                    step_two: false,
                    step_three: false,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    first_name: null,
                    last_name: null,
                    domain_name:null,
                    hear_about_us:null,
                    phone_type:null,
                    phone_no: null,
                    name: null,
                    username: null,
                    createdclicked: false,
                    registrationData: {},
                    result: [],
                    isOneActive: false,
                    isTwoActive: false,
                    isThreeActive: false
                }),

                mounted() {
                    this.isOneActive = true;
                },

                methods: {
                    validateForm: function(scope) {
                        var this_this = this;
                        this.$validator.validateAll(scope).then(function (result) {

                            if (result) {
                                if (scope == 'step-one') {
                                    this_this.catchResponseOne();
                                } else if (scope == 'step-two') {
                                    this_this.catchResponseTwo();
                                } else if (scope == 'step-three') {
                                    this_this.catchResponseThree();
                                }
                            }
                        });
                    },

                    stepNav(step) {
                        if (step == 1) {
                            if (this.isThreeActive == true || this.isTwoActive == true){
                                this.step_three = false;
                                this.step_two = false;
                                this.step_one = true;

                                this.isThreeActive = false;
                                this.isTwoActive = false;
                                this.isOneActive = true;
                            }
                        } else if (step == 2) {
                            if (this.isThreeActive == true){
                                this.step_three = false;
                                this.step_one = false;
                                this.step_two = true;

                                this.isThreeActive = false;
                                this.isOneActive = false;
                                this.isTwoActive = true;
                            }
                        }
                    },

                    catchResponseOne () {
                        var o_this = this;

                        axios.post('{{ route('company.validate.step-one') }}', {
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                        }).then(function (response) {
                            o_this.step_two = true;
                            o_this.step_one = false;
                            o_this.isOneActive = false;
                            o_this.isTwoActive = true;

                            o_this.errors.clear();
                        }).catch(function (errors) {
                            serverErrors = errors.response.data.errors;

                            o_this.$root.addServerErrors('step-one');
                        });
                    },

                    catchResponseTwo () {
                        var o_this = this;

                        axios.post('{{ route('company.validate.step-two') }}', {
                            name: this.name,
                        }).then(function (response) {
                            o_this.step_three = true;
                            o_this.step_two = false;
                            o_this.isTwoActive = false;
                            o_this.isThreeActive = true;
                            o_this.errors.clear();
                        }).catch(function (errors) {
                            serverErrors = errors.response.data.errors;

                            o_this.$root.addServerErrors('step-two');
                        });
                    },
                    catchResponseThree () {
                        this.createdclicked = true;

                        var o_this = this;

                        axios.post('{{ route('company.validate.step-three') }}', {
                            first_name: this.first_name,
                            phone_no: this.phone_no,
                            phone_type: this.phone_type,
                            hear_about_us: this.hear_about_us,
                        }).then(function (response) {
                            o_this.errors.clear();

                            o_this.sendDataToServer();
                        }).catch(function (errors) {

                            serverErrors = errors.response.data.errors;

                            o_this.createdclicked = false;

                            o_this.$root.addServerErrors('step-three');
                        });
                    },

                    handleErrorResponse (response, scope) {
                        serverErrors = response.data.errors;

                        this.$root.addServerErrors(scope);
                    },

                    sendDataToServer () {
                        var o_this = this;

                        return axios.post('{{ route('company.create.store') }}', {
                            email: this.email,
                            first_name: this.first_name,
                            last_name: this.last_name,
                            phone_no: this.phone_no,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            name: this.name,
                            domain_name: $("#store-input").val(),
                            hear_about_us: this.hear_about_us,
                            phone_type:this.phone_type,
                            username: $("#store-input").val()
                        }).then(function (response) {
                            window.location.href = response.data.redirect+'?param='+response.data.param;
                        }).catch(function (errors) {
                            serverErrors = errors.response.data.errors;

                            o_this.createdclicked = false;

                            for (i in serverErrors) {
                                window.flashMessages = [{'type': 'alert-error', 'message': serverErrors[i]}];
                            }

                            o_this.$root.addFlashMessages();
                            o_this.$root.addServerErrors('step-one');
                            o_this.$root.addServerErrors('step-two');
                            o_this.$root.addServerErrors('step-three');
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection
