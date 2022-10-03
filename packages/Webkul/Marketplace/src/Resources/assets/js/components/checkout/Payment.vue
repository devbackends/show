<template>
    <div class="step-content " :class="!isActive ? 'disabled' : ''" id="payment-section">
        <div class="form-container">
            <Accordian ref="Accordion" :disabled="!isActive" :title="'Shipping information'" :active="isActive">
                <div class="form-header" slot="header">
                    <h3 class="display-inbl">
                        Payment information
                    </h3>
                </div>
                <div class="payment-methods" slot="body" v-if="isActive">
                    <template v-for="(payment,index) in paymentMethods">
                        <div class="form-group mb-3" :class="selectedPayment === payment.method? 'font-weight-bold': ''">
                            <div class="custom-control custom-radio">
                                <input
                                                type="radio"
                                                v-model="selectedPayment"
                                                :name="payment.method"
                                                :value="payment.method"
                                                :id="payment.method"
                                                class="custom-control-input">
                                <label class="custom-control-label" :for="payment.method">{{ payment.method_title }}
                                </label>
                            </div>
                            <ClearentPayment v-if="payment.method === 'clearent'" @setValid="setValid($event, payment.method)"/>

                            <FluidPayment ref="fluidPayment" :selectedPayment="selectedPayment" v-if="['fluid','seller-fluid'].includes(payment.method)" @setAdditionalInfo="setAdditionalInfo($event)"
                                          @setValid="setValid($event, payment.method)" />

                            <BlueDogPayment ref="bluedogPayment" :selectedPayment="selectedPayment" v-if="payment.method === 'bluedog'"  @setAdditionalInfo="setAdditionalInfo($event)"
                                          @setValid="setValid($event, payment.method)" />
                            <StripePayment v-if="payment.method === 'stripe'" :sellerId="$store.getters.getCart.seller_id" @setAdditionalInfo="setAdditionalInfo($event)"
                                          @setValid="setValid($event, payment.method)" />
                            <AuthorizePayment ref="AuthorizePayment" v-if="payment.method === 'authorize'" :sellerId="$store.getters.getCart.seller_id" @setAdditionalInfo="setAdditionalInfo($event)"
                                           @setValid="setValid($event, payment.method)" />

                        </div>
                        <div class="checkout__payments-commitment-check" v-if="isSimplePaymentMethod(payment.method) && selectedPayment === payment.method">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="paymentCommitment" v-model="selectedPaymentMethodConfirmation">
                                <label class="custom-control-label" for="paymentCommitment">By selecting this option, I am making a firm commitment to the seller to purchase this product</label>
                            </div>
                        </div>
                    </template>
                    <div class="accordian-content--loading" v-show="isLoading">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </Accordian>
        </div>
    </div>
</template>

<script>
    import Accordian from './Accordian'
    import ClearentPayment from "./clearent/ClearentPayment";
    import {API_ENDPOINTS} from "../../constant";
    import FluidPayment from "./fluid/FluidPayment";
    import StripePayment from "./stripe/StripePayment";
    import AuthorizePayment from "./authorize/AuthorizePayment";
    import BlueDogPayment from "./bluedog/BlueDogPayment";

    export default {
        name: "Payment",
        components: {
            FluidPayment, BlueDogPayment, StripePayment, AuthorizePayment , Accordian, ClearentPayment,
        },
        data: () => ({
            additionalInfo: {},
            paymentMethods: [],
            selectedPayment: null,
            selectedPaymentMethodConfirmation: false,
            selectedPaymentMethodConfirmationInitialSet: false,
            validMethods: [
                {method: 'cashsale', valid: true},
                {method: 'banktransfer', valid: true},
                {method: 'check', valid: true},
            ],
            isLoading: false,
        }),
        props: {
            isActive: Boolean
        },
        async mounted() {
            let res = await this.$http.get(API_ENDPOINTS.getPaymentMethods.replace('{sellerId}', this.$store.getters.getCart.seller_id)).catch(err => {
                console.log(err);
            });
            if(typeof res.data.data.methods != 'undefined'){
                this.paymentMethods = res.data.data.methods;
            }
            let cart = this.$store.getters.getCart;
           if (cart.payment) {
                if (cart.payment.method && !this.selectedPayment) {
                    this.selectedPayment = cart.payment.method;
                }
            }else{
               if(typeof res.data.data.methods != 'undefined'){
                   if(res.data.data.methods.length > 0){
                       this.selectedPayment= res.data.data.methods[0].method;
                   }
               }
                if(this.selectedPayment=='fluid'){
                    if(typeof this.$refs.fluidPayment != 'undefined'){
                        this.$refs.fluidPayment.selectFirstCard();
                    }
                }
               if(this.selectedPayment=='bluedog'){
                   if(typeof this.$refs.bluedogPayment != 'undefined'){
                       this.$refs.bluedogPayment.selectFirstCard();
                   }
               }
            }
        },
        watch: {
            selectedPayment() {
                this.$store.commit('setStepToInactive', 'confirmation');
                this.$store.commit('setStepToInactive', 'confirmed');

                if (this.isSimplePaymentMethod(this.selectedPayment)) {

                    let cart = this.$store.getters.getCart;
                    if (cart.payment && !this.selectedPaymentMethodConfirmationInitialSet) {
                        this.selectedPaymentMethodConfirmationInitialSet = true;
                        this.selectedPaymentMethodConfirmation = true;
                    } else {
                        this.selectedPaymentMethodConfirmationInitialSet = true;
                        this.selectedPaymentMethodConfirmation = false;
                    }
                } else {
                    this.checkValid();
                }
            },
            selectedPaymentMethodConfirmation() {
                if (this.isSimplePaymentMethod(this.selectedPayment) && this.selectedPaymentMethodConfirmation) {
                    this.checkValid();
                }
            },
            isActive() {
                if (this.isActive) {
                    this.checkValid();
                }
            }
        },
        methods: {
            setAdditionalInfo(data) {
                this.additionalInfo = data;
            },
            setValid(valid, method) {
            	this.validMethods = this.validMethods.filter(item => item.method !== method);
            	this.validMethods.push({method: method, valid: valid});
            	this.checkValid();
            },
            isSimplePaymentMethod(code) {
                return code === 'cashsale' || code === 'check' || code === 'banktransfer'
            },
            async checkValid() {

                for (let item of this.validMethods) {

                    if (item.method === this.selectedPayment && item.valid) {
                        //document.body.style.cursor = "wait";
                        this.isLoading = true;
                        let res = await this.$http.post(API_ENDPOINTS.postCheckoutSavePaymentMethod, {
                            sellerId: this.$store.getters.getCart.seller_id,
                            additional: this.additionalInfo,
                            payment: {
                                method: this.selectedPayment,
                            },
                        }).catch(err => {
                            console.log(err);
                        });
                        this.$store.commit('setCart', res.data.data.cart);
                        //document.body.style.cursor = "default";
                        this.isLoading = false;
                        this.$emit('done');
                        break;
                    }
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
