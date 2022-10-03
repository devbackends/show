<template>
    <div class="checkout__summary">
        <h3 class="heading">Order summary</h3>
        <div class="checkout__summary-details">
            <div class="d-flex checkout__summary-details-quantity">
                <p class="mr-auto">{{ getCartItemsAmount }} item(s) in cart</p>
                <p class="ml-auto">
                    <a href="/checkout/cart">Details</a>
                </p>
            </div>

            <div class="d-flex">
                <p class="mr-auto">Item Subtotal</p>
                <p class="ml-auto">{{ getItemsSubTotal }}</p>
            </div>

            <div class="d-flex">
                <p class="mr-auto">Shipping charges</p>
                <p class="ml-auto">{{ getOtherDeliveryCharges }}</p>
            </div>
            <div v-if="$store.getters.getCart.base_discount > 0" class="shopping-cart__summary-info-item">
                <p>Discount Amount</p>
                <p  v-text="'$'+parseFloat($store.getters.getCart.base_discount).toFixed(2)"></p>
            </div>
<!--            <div class="d-flex">
                <p class="mr-auto">Tax 0%</p>
                <p class="ml-auto">{{ getTaxTotal }}</p>
            </div>

            <div class="d-flex">
                <p class="mr-auto">Firearm Processing</p>
                <p class="ml-auto">{{ getFflDeliveryCharges }}</p>
            </div>-->
            <Coupon  @onApplyCoupon="onApplyCoupon" :cart="$store.getters.getCart"></Coupon>
            <div class="d-flex checkout__summary-details-total" id="grand-total-detail">
                <p class="mr-auto">Total</p>
                <p class="ml-auto" id="grand-total-amount-detail">{{ getTotal }}</p>
            </div>
        </div>
        <div class="checkout__summary-steps">
            <div v-if="!customer" class="checkout__summary-steps-item checkout__summary-steps-item--active">
                <p class="checkout__summary-steps-number">1</p>
                <p class="checkout__summary-steps-text">Customer information</p>
            </div>
            <div class="checkout__summary-steps-item checkout__summary-steps-item"
                 :class="!$store.getters.getCartOptions.steps.billingAddress ? '' : 'checkout__summary-steps-item--active'">
                <p class="checkout__summary-steps-number" v-text="!customer ? '2' : '1' "></p>
                <p class="checkout__summary-steps-text">Select the billing address</p>
            </div>
            <div class="checkout__summary-steps-item"
                :class="!$store.getters.getCartOptions.steps.shipping ? '' : 'checkout__summary-steps-item--active'">
                <p class="checkout__summary-steps-number"  v-text="!customer ? '3' : '2' "></p>
                <p class="checkout__summary-steps-text">Shipping information</p>
            </div>
            <div class="checkout__summary-steps-item"
                :class="!$store.getters.getCartOptions.steps.payment ? '' : 'checkout__summary-steps-item--active'">
                <p class="checkout__summary-steps-number"  v-text="!customer ? '4' : '3' "></p>
                <p class="checkout__summary-steps-text">Payment information</p>
            </div>
            <div class="checkout__summary-steps-item"
                :class="!$store.getters.getCartOptions.steps.confirmation ? '' : 'checkout__summary-steps-item--active'">
                <p class="checkout__summary-steps-number"  v-text="!customer ? '5' : '4' "></p>
                <p class="checkout__summary-steps-text">Confirmation</p>
            </div>

            <div class="checkout__summary-steps-button"
                :class="$store.getters.getCartOptions.steps.confirmed ? 'checkout__summary-steps-button--active' : ' '">
                <i class="fas fa-check-circle"></i>
                <submit-button type="button" id="custom-submit-button" :disabled="!$store.getters.getCartOptions.steps.confirmed" text="Place the order" :loading="isLoading" :cssClass="[{'checkout-button' : true}, $store.getters.getCartOptions.steps.confirmed ? 'btn-primary' : 'btn-outline-gray-dark']" @clickEvent="onPlaceOrderClick"></submit-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Summary",
    data: function () {
        return {
            isLoading: false
        };
    },
    props: ["customer"],
    computed: {
        getCartItemsAmount: function () {
            return this.$store.getters.getCart.items_count;
        },
        getItemsSubTotal: function () {
            return "$" + parseFloat(this.$store.getters.getCart.sub_total).toFixed(2).toString();
        },
        getTaxTotal: function () {
            return parseFloat(this.$store.getters.getCart.tax_total).toFixed(2).toString();
        },
        getOtherDeliveryCharges: function () {
            const cart = this.$store.getters.getCart;
            if(this.getFflDeliveryCharges !='$0.00'){  return this.getFflDeliveryCharges; }
            if (!cart.selected_shipping_rate) return "$0.00";

            let price = cart.selected_shipping_rate.base_price;
            if (cart.per_product_shipping_rate && cart.selected_shipping_rate.method !== 'product_shipping_price_total') {
                price += cart.per_product_shipping_rate.base_price
            }

            return (
                "$" +
                parseFloat(price)
                    .toFixed(2)
                    .toString()
            );
        },
        getFflDeliveryCharges: function () {
            const cart = this.$store.getters.getCart;
            if (!cart.selected_ffl_shipping_rate) return "$0.00";

            let price = cart.selected_ffl_shipping_rate.base_price;
            if (cart.per_product_ffl_shipping_rate && cart.selected_ffl_shipping_rate.method !== 'ffl_product_shipping_price_total') {
                price += cart.per_product_ffl_shipping_rate.base_price
            }

            return (
                "$" +
                parseFloat(price)
                    .toFixed(2)
                    .toString()
            );
        },
        getTotal: function () {
            return (
                "$" + parseFloat(this.$store.getters.getCart.base_grand_total).toFixed(2).toString()
            );
        },
    },
    methods: {
        onPlaceOrderClick: function () {
            this.isLoading = true;
            if (!this.$store.getters.getCartOptions.steps.confirmation) return;
            this.$emit("done", {});
        },
        onApplyCoupon(){
         location.reload();

        }
    },

};
</script>

<style scoped>
.checkout-button {
    position: relative;
    display: flex;
    justify-content: center;
}
.checkout-button-spinner {
    position: absolute;
}
.checkout-button--hidden {
    opacity: 0;
}
</style>