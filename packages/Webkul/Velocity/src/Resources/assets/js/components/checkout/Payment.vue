<template>
    <div class="step-content " v-bind:class="!isActive ? 'disabled' : ''" id="payment-section">
        <div class="form-container">
            <Accordian :disabled="!isActive" :title="'Shipping information'" :active="isActive">
                <div class="form-header" slot="header">
                    <h3 class="display-inbl">
                        Payment information
                    </h3>
                </div>
                <div class="payment-methods" slot="body" v-if="isActive">
                    <template v-for="payment in paymentMethods">
                        <div class="payment-method" v-bind:class="selectedPayment === payment.method? 'font-weight-bold': ''">
                            <div class="radio-input-container">

                                <div class="radio-button">
                                    <div class="radio-button__input">
                                        <input checked v-validate="'required'"
                                                type="radio"
                                                v-model="selectedPayment"
                                                :name="payment.method"
                                                :value="payment.method">
                                        <label :for="payment.method"></label>
                                    </div>
                                    <div class="radio-button__label">{{ payment.method_title }}</div>
                                </div>
                            

                            </div>
                            <clearent-payment
                                v-if="payment.method === 'clearent'"
                                v-on:setValid="setValid($event, payment.method)"
                            ></clearent-payment>
                        </div>
                    </template>
                </div>
            </Accordian>
        </div>
    </div>
</template>

<script>
    import Accordian from './Accordian'
    import ClearentPayment from "./clearent/ClearentPayment";
    import {API_ENDPOINTS} from "../../constant";

    export default {
        name: "Payment",
        components: {
            Accordian, ClearentPayment,
        },
        data: function () {
            return {
                paymentMethods: [],
                selectedPayment: null,
                validMethods: [] = [],
            };
        },
        props: {
            isActive: Boolean,
            cart: {
                type: Object,
            }
        },
        mounted() {
            this.$http.get(API_ENDPOINTS.getPaymentMethods)
                .then(res => {
                    this.paymentMethods = res.data.data.methods;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        watch: {
            selectedPayment: function (item) {
                this.checkValid();
            },
            cart: function () {
                if (!this.cart) return;
                if (!this.cart.payment) return;
                if (this.cart.payment.method && !this.selectedPayment) {
                    this.selectedPayment = this.cart.payment.method;
                }
            }
        },
        methods: {
            setValid(valid, method){
            	this.validMethods = this.validMethods.filter(item => item.method !== method);
            	this.validMethods.push({method: method, valid: valid});
            	this.checkValid();
            },
            checkValid(){
                let paymentValid = false;
                for (let item of this.validMethods) {
                    if(item.method === this.selectedPayment && item.valid) {
                        this.$emit('change', this.selectedPayment);
                        paymentValid = true;
                        break;
                    }
                }
                if(!paymentValid){
                    this.$emit('change', null);
                }
            }
        }
    }
</script>

<style scoped>
    .radio-input-container {
        width: 100%;
    }

    .radio-text-container {
        float: none;
        margin-left: 3rem;
    }
</style>
