<template>
    <div class="row py-5" v-if="ready">
        <div class="col-lg-8">
            <div class="checkout__form">
                <!--         <div class="checkout__form-title">
                  <p>Secure checkout</p>

                </div>-->
                <div class="checkout__form-customer">
                    <customer-info :cart="cart" v-on:change="onAddressUpdate"/>
                </div>
                <div class="checkout__form-shipping">
                    <shipping
                        v-on:changeFflShipping="onChangeFflShipping"
                        v-on:changeOtherShipping="onChangeOtherShipping"
                        v-on:changeFflShippingRate="onChangeFflShippingRate"
                        v-on:changeOtherItemShippingRate="onChangeOtherItemShippingRate"
                        :shipToBillingAddress="shipToBillingAddress ? this.address : null"
                        :cart="cart"
                        :cart-items="cart ? cart.items : []"
                        :is-active="steps.includes('shipping')"
                    />
                </div>
                <div class="checkout__form-payment">
                    <payment
                        :cart="cart"
                        v-on:change="onPaymentChange"
                        :is-active="steps.includes('payment')"
                    />
                </div>
                <div class="checkout__form-confirmation">
                    <confirmation
                        v-on:change="onConfirmationChange"
                        :shipping="shipping"
                        :cart-items="cart ? cart.items : []"
                        :is-active="steps.includes('confirmation')"
                    />
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="order-summary-container">
                <Summary
                    v-on:onPlaceOrder="onPlaceOrder"
                    :cart="cart"
                    :steps="steps"
                    :cart-items="cart ? cart.items : []"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerInfo from "./CustomerInfo";
    import Shipping from "./Shipping";
    import Payment from "./Payment";
    import Confirmation from "./Confirmation";
    import Summary from "./Summary";
    import {API_ENDPOINTS, FIREARM_FAMILY} from "../../constant";

    export default {
        name: "checkout",
        inject: {$validator: "$validator"},
        components: {
            CustomerInfo,
            Shipping,
            Payment,
            Confirmation,
            Summary,
        },
        props: {
            checkoutSuccessUrl: String,
        },
        data: function () {
            return {
                address: null,
                customerInfo: null,
                steps: ["address"],
                cart: null,
                shipToBillingAddress: false,
                shipping: {},
                payment: null,
                confirmed: false,
                ready: false,
            };
        },
        watch: {
            shipping: {
                handler() {
                    let shippingFilled = true;
                    for (let item in this.shipping) {
                        if (!this.shipping[item]) {
                            shippingFilled = false;
                            break;
                        }
                    }
                    if (shippingFilled) {
                        this.steps.push("payment");
                    }
                },
                deep: true,
            },
        },
        methods: {
            onAddressUpdate: function (address) {
                this.address = address;
                this.updateCart("billingAddress");
            },
            onPaymentChange: function (payment) {
                this.payment = payment;
                if (!this.payment) {
                    this.steps = this.steps.filter((item) => item !== "confirmation");
                    return;
                }
                this.updateCart("payment");
            },
            onChangeFflShipping: function (shipping) {
                if (!shipping) return;
                this.shipping.selectedFflShipping = shipping;
                this.updateCart("shippingAddressFfl");
            },
            onChangeOtherShipping: function (shipping) {
                if (!shipping) return;
                this.shipping.selectedOtherShipping = shipping;
                this.updateCart("shippingAddressOther");
            },
            onChangeFflShippingRate: function (shipping) {
                if (!shipping) return;
                this.shipping.fflItemsShippingRateSelected = shipping;
                this.updateCart("shippingFflRate");
            },
            onChangeOtherItemShippingRate: function (shipping) {
                if (!shipping) return;
                this.shipping.otherItemsShippingRateSelected = shipping;
                this.updateCart("shippingOtherRate");
            },
            onConfirmationChange: function (confirmed) {
                this.confirmed = confirmed;
                if (!confirmed) {
                    this.steps = this.steps.filter((el) => el !== "confirmed");
                } else {
                    this.steps.push("confirmed");
                }
            },
            updateCart: function (action) {
                document.body.style.cursor = "wait";
                switch (action) {
                    case "billingAddress": {
                        this.sendCartUpdateRequest(
                            API_ENDPOINTS.postCheckoutSaveBillingAddress,
                            {
                                billing: {
                                    ...this.address,
                                    // address_id: this.address.id,
                                    email: this.customerInfo.email,
                                },
                            }
                        )
                            .then((res) => {
                                this.cart = res;
                                eventBus.$emit("addressChanged", this.address);
                                this.steps.push("shipping");
                                document.body.style.cursor = "default";
                            })
                            .catch((err) => {
                                //TODO add redirect to cart
                            });
                        break;
                    }
                    case "shippingAddressOther": {
                        this.sendCartUpdateRequest(
                            API_ENDPOINTS.postCheckoutShippingAddress,
                            {
                                shipping: {
                                    ...this.shipping.selectedOtherShipping,
                                    email: this.customerInfo.email,
                                },
                            }
                        )
                            .then((res) => {
                                this.cart = res;
                                eventBus.$emit("shippingChanged", this.address);
                                document.body.style.cursor = "default";
                            })
                            .catch((err) => {
                                //TODO add redirect to cart
                            });
                        break;
                    }
                    case "shippingAddressFfl": {
                        this.sendCartUpdateRequest(
                            API_ENDPOINTS.postCheckoutSaveFflShippingAddress,
                            {
                                shipping: {
                                    company_name: this.shipping.selectedFflShipping.company_name,
                                    first_name: this.shipping.selectedFflShipping.contact_name,
                                    last_name: this.shipping.selectedFflShipping.contact_name,
                                    ffl_id: this.shipping.selectedFflShipping.ffl_id,
                                    address1: [this.shipping.selectedFflShipping.street_address],
                                    city: this.shipping.selectedFflShipping.city,
                                    phone: this.shipping.selectedFflShipping.phone,
                                    postcode: this.shipping.selectedFflShipping.zip_code,
                                    country: "US",
                                    state: this.shipping.selectedFflShipping.state,
                                },
                            }
                        )
                            .then((res) => {
                                this.cart = res;
                                eventBus.$emit("shippingChanged");
                                document.body.style.cursor = "default";
                            })
                            .catch((err) => {
                                //TODO add redirect to cart
                            });
                        break;
                    }
                    case "shippingOtherRate": {
                        this.sendCartUpdateRequest(
                            API_ENDPOINTS.postCheckoutSaveShippingRate,
                            {
                                shipping_method: this.shipping.otherItemsShippingRateSelected,
                            }
                        )
                            .then((res) => {
                                this.cart = res;
                                document.body.style.cursor = "default";
                            })
                            .catch((err) => {
                                //TODO add redirect to cart
                            });
                        break;
                    }
                    case "payment": {
                        this.sendCartUpdateRequest(
                            API_ENDPOINTS.postCheckoutSavePaymentMethod,
                            {
                                payment: {
                                    method: this.payment,
                                },
                            }
                        )
                            .then((res) => {
                                this.cart = res;
                                this.steps.push("confirmation");
                                document.body.style.cursor = "default";
                            })
                            .catch((err) => {
                                //TODO add redirect to cart
                            });
                        break;
                    }
                    case "shippingFflRate": {
                        this.sendCartUpdateRequest(
                            API_ENDPOINTS.postCheckoutSaveFflShippingRate,
                            {
                                shipping_method: this.shipping.fflItemsShippingRateSelected,
                            }
                        )
                            .then((res) => {
                                this.cart = res;
                                document.body.style.cursor = "default";
                            })
                            .catch((err) => {
                                //TODO add redirect to cart
                            });
                        break;
                    }
                }
            },
            sendCartUpdateRequest: function (url, body) {
                return new Promise((resolve, reject) => {
                    this.$http
                        .post(url, body)
                        .then((res) => {
                            resolve(res.data.data.cart);
                        })
                        .catch((err) => {
                            console.log(err);
                            reject(err);
                        });
                });
            },
            onPlaceOrder: function () {
                this.$http
                    .post(API_ENDPOINTS.postCheckoutSaveOrder)
                    .then((res) => {
                        if (res.data.redirect_url) {
                            window.location.href = res.data.redirect_url;
                        } else {
                            window.location.href = this.checkoutSuccessUrl;
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            },
            setShippingObject: function (items) {
                items.forEach((item) => {
                    if (
                        item.product.attribute_family.code.toLowerCase() === FIREARM_FAMILY
                    ) {
                        this.shipping = {
                            ...this.shipping,
                            selectedOtherShipping: null,
                            fflItemsShippingRateSelected: null,
                        };
                    } else {
                        this.shipping = {
                            ...this.shipping,
                            selectedOtherShipping: null,
                            otherItemsShippingRateSelected: null,
                        };
                    }
                });
            },
        },
        async mounted() {
            const res = await this.$http
                .get(API_ENDPOINTS.getCart)
                .catch((err) => console.log(err));
            this.cart = res.data.data;
            eventBus.$on("shipToBillingAddress", (shipToBillingAddress) => {
                this.shipToBillingAddress = shipToBillingAddress;
            });
            const resT = await this.$http
                .get(API_ENDPOINTS.getCustomerInfo)
                .catch((err) => console.log(err));
            if (resT) {
                this.customerInfo = resT.data.data;
                eventBus.$emit("getCustomerInfo", resT.data);
            } else {
                this.customerInfo = {email: null};
            }
            this.ready = true;
            this.setShippingObject(this.cart.items);
            eventBus.$on('changeEmail', email => {
                this.customerInfo.email = email;
            });
        },
    };
</script>

<style scoped>
    .accordian {
        border-bottom: 0 !important;
    }
</style>
