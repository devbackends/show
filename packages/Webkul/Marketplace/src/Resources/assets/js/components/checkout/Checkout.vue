<template>
    <div class="checkout__wrapper" v-if="ready">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 col-xl-8 order-1 order-lg-1">
                    <div class="checkout__form" id="checkout-form">
                        <div class="checkout__form-label h3">
                            <i class="far fa-shield-check"></i>
                            Secure checkout
                        </div>

                        <div v-if="!customer" class="checkout__form-customer-authentication">
                            <CustomerAuthentication :terms="terms" @done="onCustomerAuthentication"/>
                        </div>
                        <div class="checkout__form-customer">
                            <BillingAddress ref="childComponent" @done="onBillingAddressSelected" :is-active="$store.getters.getCartOptions.steps.billingAddress"/>
                        </div>
                        <div class="checkout__form-shipping">
                            <Shipping @done="onShippingSelected" :is-active="$store.getters.getCartOptions.steps.shipping"/>
                        </div>
                        <div class="checkout__form-payment">
                            <Payment ref="Payment" @done="onPaymentSelected" :is-active="$store.getters.getCartOptions.steps.payment"/>
                        </div>
                        <div class="checkout__form-confirmation">
                            <Confirmation @done="onConfirmation" :is-active="$store.getters.getCartOptions.steps.confirmation"/>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-5 col-xl-4 order-2 order-lg-2 mt-4 mt-md-0">
                    <Summary :customer="customer" @done="onPlaceOrder"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerAuthentication from "./CustomerAuthentication";
    import BillingAddress from "./BillingAddress";
    import Shipping from "./Shipping";
    import Payment from "./Payment";
    import Confirmation from "./Confirmation";
    import Summary from "./Summary";
    import {API_ENDPOINTS} from "../../constant";

    export default {
        name: "checkout",
        inject: {$validator: "$validator"},
        components: {
            CustomerAuthentication,
            BillingAddress,
            Shipping,
            Payment,
            Confirmation,
            Summary,
        },
        props: {
            checkoutSuccessUrl: String,
            cart: Object,
            customer: Object,
            seller: Object,
            terms: String
        },
        data: function () {
            return {
                ready: false,
                customerData:null,
            };
        },
        async mounted() {
            this.$store.commit('setCart', this.cart);
            if(this.customer){
                this.$store.commit('setCustomer', this.customer);
                this.$store.commit('setBillingAddress', true);
            }
            this.$store.commit('setSeller', this.seller);
            this.$store.commit('setCartOptions');
            this.customerData=this.customer;
            await this.$store.dispatch('getCustomerAddresses');
            await this.$store.dispatch('getStates');
            this.ready = true;
        },
        computed: {
            isStepActive() {
                return step => this.$store.getters.getCartOptions.steps[step];
            }
        },
        methods: {
            async onCustomerAuthentication(data) {
                    this.customer=data.customer;
                    this.$store.commit('setCustomer', data.customer);
                    this.AddCustomerTocart();
                    this.$store.commit('setBillingAddress', true);
                    const customerData= await this.$store.dispatch('getCustomerAddresses');
                    this.$refs.childComponent.setAddresses(customerData);
                    window.location.reload();

            },
            async AddCustomerTocart(){
                let res = await this.$http.post('/marketplace/cart/add-customer-to-cart', {
                    cart: this.cart,
                    customer: this.customer,
                }).catch(err => {
                    console.log(err);
                });
            },
            onBillingAddressSelected() {
                if (!this.$store.getters.getCart.billing_address) {
                    console.log('ERROR | Billing address has not been set');
                    return false;
                }
                if (this.$store.getters.getCartOptions.withoutShipping.only) {
                    this.$store.commit('setStepToActive', 'payment')
                } else {
                    this.$store.commit('setStepToActive', 'shipping')
                }
            },
            onShippingSelected() {
                let wrong = false;
                let cart = this.$store.getters.getCart;
                if (this.$store.getters.getCartOptions.ffl.is) {
                    if (!cart.ffl_address) {
                        wrong = true;
                    }
                    if (!cart.selected_ffl_shipping_rate) {
                        wrong = true;
                    }
                    if (!cart.ffl_shipping_method) {
                        wrong = true;
                    }
                }
                if (this.$store.getters.getCartOptions.other.is) {
                    if (!cart.shipping_address) {
                        wrong = true;
                    }
                    if (!cart.selected_shipping_rate) {
                        wrong = true;
                    }
                    if (!cart.shipping_method) {
                        wrong = true;
                    }
                }
                if (wrong) {
                    console.log('ERROR | Shipping has not been set completely');
                    return false;
                }
                this.$store.commit('setStepToActive', 'payment')
            },
            onPaymentSelected() {
                if (!this.$store.getters.getCartOptions.withoutShipping.only) {
                    if (!this.$store.getters.getCart.selected_ffl_shipping_rate
                        && !this.$store.getters.getCart.selected_shipping_rate) return false;
                }
                if (!this.$store.getters.getCart.payment) {
                    console.log('ERROR | Payment has not been set');
                    return false;
                }
                this.$store.commit('setStepToActive', 'confirmation')
            },
            onConfirmation() {
                this.$store.commit('setStepToActive', 'confirmed')
            },
            async onPlaceOrder() {
                let res = await this.$http.post(API_ENDPOINTS.postCheckoutSaveOrder, {
                    sellerId: this.$store.getters.getCart.seller_id,
                    onlyWithoutShipping: this.$store.getters.getCartOptions.withoutShipping.only,
                }).catch(err => {
                    console.log(err);
                });
                if (res.data.redirect_url) {
                    window.location.href = res.data.redirect_url;
                } else {
                    window.location.href = this.checkoutSuccessUrl;
                }
            },
        },
        watch: {
            customerData(){
                if(this.customerData){
                    this.$store.commit('setStepToActive', 'address')
                }else{
                    this.$store.commit('setStepToInactive', 'address')
                }
            }
        }

    };
</script>

<style scoped>
.gm-style .gm-style-iw-c {
    max-width: 300px !important;
    background-color: green;
}

.gm-style .gm-style-iw-d {
    box-sizing: border-box;
    overflow: hidden !important;
    padding: 10px !important;
    max-width: 300px !important;
}

.checkout__wrapper {
    background-color: #EBEBEB
}

.checkout__wrapper .container {
    padding-top: 0;

@media only screen and (min-width: 992px) {
    padding-top:

90px

;
}

}
.accordian {
    border-bottom: 0 !important;
}

</style>
