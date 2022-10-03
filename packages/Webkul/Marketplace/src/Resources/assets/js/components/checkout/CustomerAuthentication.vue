<template>
    <div class="step-content information" id="authentication-section">
        <Accordian :active="loginIsActive" title="Already a customer">
            <div class="form-header" slot="header">
                <h3 class="display-inbl">Already a customer</h3>
            </div>
            <div slot="body">
                <div class="row d-flex align-items-stretch">
                    <div class="col-12">
                        <div class="mb-4">
                            <form method="POST" data-vv-scope="login-form" @submit.prevent="onSubmitLogin('login-form',$event)">
                            <div class="custom-form-container">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-show="loginError">
                                        <strong v-text="loginErrorMessage"></strong>
                                        <button type="button" class="close" aria-label="Close" v-on:click="loginError=false">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="form-group margin_b_20" :class="[errors.has('email', 'login-form') ? 'has-error' : '']">
                                        <label for="email" class="mandatory label-style form-labels">Email</label>
                                        <input type="email" name="email"
                                               class="form-control" v-validate="'required|email'"
                                               placeholder="Email"
                                               v-model="loginEmail"
                                               data-vv-as="&quot;Email&quot;"/>
                                        <span class="control-error" v-show="errors.has('email', 'login-form')" v-text="errors.first('email', 'login-form')"></span>
                                    </div>
                                    <div class="form-group" :class="[errors.has('password', 'login-form') ? 'has-error' : '']">
                                        <label for="password"
                                               class="mandatory label-style">Password</label>
                                        <input type="password" class="form-control" name="password"
                                               v-validate="'required'"
                                               placeholder="Password"
                                               v-model="loginPassword"
                                               data-vv-as="&quot;Password&quot;">
                                        <span class="control-error" v-show="errors.has('password', 'login-form')" v-text="errors.first('password', 'login-form')"></span>
                                    </div>
                                    <div class="submit-container box-section__action">
                                        <input :disabled="loginDisable" type="submit" value="Sign In" class="btn btn-primary">
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="accordian-content--loading" v-show="isLoading">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </Accordian>
        <Accordian :active="registerIsActive" title="New customer information">
            <div class="form-header" slot="header">
                <h3 class="display-inbl">New customer information</h3>
            </div>
            <div slot="body">
                <div class="row d-flex align-items-stretch">
                    <div class="col-12">
                        <div class="mb-4">
                            <div class="custom-form-container">
                                <form method="POST" data-vv-scope="register-form" @submit.prevent="onSubmitRegister('register-form',$event)">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-show="registerError">
                                        <strong v-text="registerErrorMessage"></strong>
                                        <button type="button" class="close" aria-label="Close" v-on:click="registerError=false">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group margin_b_20" :class="[errors.has('first_name', 'register-form') ? 'has-error' : '']">
                                                <label for="first_name" class="mandatory label-style form-labels">First Name</label>
                                                <input type="text" v-model="first_name" name="first_name" id="first_name" placeholder="First Name" class="form-control" v-validate="'required'"  data-vv-as="&quot;First Name&quot;">
                                                <span class="control-error" v-if="errors.has('first_name', 'register-form')" v-text="errors.first('first_name', 'register-form')"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group margin_b_20" :class="[errors.has('last_name', 'register-form') ? 'has-error' : '']">
                                                <label for="last_name" class="mandatory label-style form-labels">Last Name</label>
                                                <input type="text" v-model="last_name" name="last_name" id="last_name" class="form-control" v-validate="'required'"  placeholder="Last Name" data-vv-as="&quot;Last Name&quot;">
                                                <span class="control-error" v-if="errors.has('last_name', 'register-form')" v-text="errors.first('last_name', 'register-form')"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group margin_b_20" :class="[errors.has('email', 'register-form') ? 'has-error' : '']">
                                        <label for="email" class="mandatory label-style form-labels">Email</label>
                                        <input type="email" v-model="email" class="form-control" id="email" name="email" v-validate="'required|email'"  placeholder="Email" data-vv-as="&quot;Email&quot;">
                                        <span class="control-error" v-if="errors.has('email', 'register-form')" v-text="errors.first('email', 'register-form')"></span>
                                    </div>
                                    <div class="form-group" :class="[errors.has('password', 'register-form') ? 'has-error' : '']">
                                        <label for="password" class="mandatory label-style">Password</label>
                                        <input type="password" v-model="password" class="form-control" name="password" id="password" v-validate="'required|min:6'" ref="password" placeholder="Password" data-vv-as="&quot;Password&quot;">
                                        <span class="control-error" v-if="errors.has('password', 'register-form')" v-text="errors.first('password', 'register-form')"></span>
                                    </div>
                                    <div class="form-group" :class="[errors.has('password_confirmation', 'register-form') ? 'has-error' : '']">
                                        <label for="password-re" class="mandatory label-style">Confirm Password</label>
                                        <input type="password" v-model="password_confirmation" id="password-re" class="form-control" name="password_confirmation" v-validate="'required|min:6|confirmed:password'" placeholder="Confirm Password" data-vv-as="&quot;Confirm Password&quot;">
                                        <span class="control-error" v-if="errors.has('password_confirmation', 'register-form')" v-text="errors.first('password_confirmation', 'register-form')"></span>
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
                                        <submit-button :disabled="!termsAndCondsChecked" id="custom-submit-button"  text="Sign Up" :loading="false"></submit-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
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
                                    <div class="modal-body" v-html="terms">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="acceptModalBtnClicked">I accept the terms and conditions</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordian-content--loading" v-show="isLoading">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </Accordian>

    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../constant";
import Accordian from "./Accordian";


export default {
    name: "CustomerAuthentication",
    inject: {$validator: "$validator"},
    components: {
        Accordian,
    },
    data: function () {
        return {
            loginIsActive:false,
            registerIsActive:true,
            customer:null,
            isLoading: false,
            loginEmail: null,
            loginPassword: null,
            first_name: null,
            last_name: null,
            email:null,
            password: null,
            password_confirmation: null,
            termsAndCondsChecked: false,
            loginError: false,
            loginErrorMessage: '',
            registerError: false,
            registerErrorMessage: '',
            loginDisable: false
        };
    },
    props:['terms'],
    mounted() {

    },
    computed: {

    },
    methods: {
        onSubmitLogin(formScope,e){
            this.loginDisable=true;
            e.preventDefault();
            this.$validator.validateAll(formScope).then((result) => {
                if (result) {
                    var this_this = this;
                    this_this.$http.post("/customer/login", {'email':this_this.loginEmail,'password':this_this.loginPassword,'async':true})
                        .then(function(response) {
                            if(response.status==200){
                                if(response.data.code==200){
                                    this_this.customer=response.data.customer;
                                    this_this.$toast.success(response.data.message, {
                                        position: 'top-right',
                                        duration: 5000,
                                    });
                                    this_this.loginIsActive=false;
                                    this_this.registerIsActive=false;
                                    this_this.$emit('done',{customer: response.data.customer});
                                }else{
                                    this_this.loginDisable=false;
                                    this_this.loginError=true;
                                    this_this.loginErrorMessage=response.data.message;
                                }

                            }else{
                                this_this.loginDisable=false;
                                this_this.$toast.error(response.data.message, {
                                    position: 'top-right',
                                    duration: 5000,
                                });
                            }
                        })
                        .catch(function (error) {

                        })
                }
            });

        },
        onSubmitRegister(formScope,e){
            e.preventDefault();
            this.$validator.validateAll(formScope).then((result) => {
                if (result) {
                    var this_this = this;
                    this_this.$http.post("/customer/register", {'first_name': this_this.first_name, 'last_name': this_this.last_name, 'email':this_this.email,'password':this_this.password,'password_confirmation':this_this.password_confirmation,'async':true})
                        .then(function(response) {
                            if(response.status==200){
                                if(response.data.code==200){
                                    this_this.customer=response.data.customer;
                                    this_this.$toast.success(response.data.message, {
                                        position: 'top-right',
                                        duration: 5000,
                                    });
                                    this_this.loginIsActive=false;
                                    this_this.registerIsActive=false;
                                    this_this.$emit('done',{customer: response.data.customer});
                                }else{
                                    this_this.registerError=true;
                                    this_this.registerErrorMessage=response.data.message;
                                }

                            }else{
                                this_this.$toast.error(response.data.message, {
                                    position: 'top-right',
                                    duration: 5000,
                                });
                            }
                        })
                        .catch(function (error) {

                        })
                }
            });
        },
        emitResult() {
            this.$emit('done');
        },
        acceptModalBtnClicked() {
            this.termsAndCondsChecked = true;
        }
    },
    watch: {

    },
};
</script>

<style scoped>
</style>
