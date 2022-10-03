<template>
  <div class="checkout__summary">
    <h3 class="heading">Order summary</h3>
    <div class="checkout__summary-details">
      <div class="d-flex checkout__summary-details-quantity">
        <p class="mr-auto">{{getCartItemsAmount}} items in cart</p>
        <a class="ml-auto" href="#">Details</a>
      </div>
      <div class="d-flex">
        <p class="mr-auto">Item Subtotal</p>
        <p class="ml-auto">{{getItemsSubTotal}}</p>
      </div>
      <div class="d-flex">
        <p class="mr-auto">Delivery charges</p>
        <p class="ml-auto">{{getOtherDeliveryCharges}}</p>
      </div>
      <div class="d-flex">
        <p class="mr-auto">Tax 0%</p>
        <p class="ml-auto">{{getTaxTotal}}</p>
      </div>

      <div class="d-flex">
        <p class="mr-auto">Firearm Processing</p>
        <p class="ml-auto">{{getFflDeliveryCharges}}</p>
      </div>
      <div class="d-flex checkout__summary-details-total" id="grand-total-detail">
        <p class="mr-auto">Total</p>
        <p class="ml-auto" id="grand-total-amount-detail">{{getTotal}}</p>
      </div>
    </div>
    <div class="checkout__summary-steps">
      <div class="checkout__summary-steps-item checkout__summary-steps-item--active">
        <p class="checkout__summary-steps-number">1</p>
        <p class="checkout__summary-steps-text">Select the billing address</p>
      </div>
      <div
        class="checkout__summary-steps-item"
        v-bind:class="!steps.includes('shipping') ? '' : 'checkout__summary-steps-item--active'"
      >
        <p class="checkout__summary-steps-number">2</p>
        <p class="checkout__summary-steps-text">Shipping information</p>
      </div>
      <div
        class="checkout__summary-steps-item"
        v-bind:class="!steps.includes('payment') ? '' : 'checkout__summary-steps-item--active'"
      >
        <p class="checkout__summary-steps-number">3</p>
        <p class="checkout__summary-steps-text">Payment information</p>
      </div>
      <div
        class="checkout__summary-steps-item"
        v-bind:class="!steps.includes('confirmation') ? '' : 'checkout__summary-steps-item--active'"
      >
        <p class="checkout__summary-steps-number">4</p>
        <p class="checkout__summary-steps-text">Confirmation</p>
      </div>

      <div class="checkout__summary-steps-button" v-bind:class="steps.includes('confirmed') ? 'checkout__summary-steps-button--active' : ' '">
          <i class="fas fa-check-circle"></i>
          <button
            v-on:click="onPlaceOrderClick"
            v-bind:disabled="!steps.includes('confirmed')"
            type="button"
             v-bind:class="steps.includes('confirmed') ? 'btn-primary' : 'btn-outline-gray-dark'"
            class="btn"
          >
            PLACE THE
            ORDER
          </button>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Summary",
  props: {
    cartItems: {
      type: Array,
      default: [],
    },
    steps: {
      type: Array,
      default: [],
    },
    cart: {
      type: Object,
    },
  },
  data: function () {
    return {
      cartData: null,
    };
  },
  watch: {
    cart: function () {},
  },
  methods: {
    onPlaceOrderClick: function () {
      if (!this.steps.includes("confirmed")) {
        return;
      }
      this.$emit("onPlaceOrder", {});
    },
  },
  computed: {
    getCartItemsAmount: function () {
      if (!this.$props.cart) return "";
      return this.$props.cart.items_count;
    },
    getItemsSubTotal: function () {
      if (!this.$props.cart) return "";
      return "$" + parseFloat(this.$props.cart.sub_total).toFixed(2).toString();
    },
    getTaxTotal: function () {
      if (!this.$props.cart) return "";
      return parseFloat(this.$props.cart.tax_total).toFixed(2).toString();
    },
    getOtherDeliveryCharges: function () {
      if (!this.$props.cart) return "FREE";
      if (!this.$props.cart.selected_shipping_rate) return "FREE";
      return (
        "$" +
        parseFloat(this.$props.cart.selected_shipping_rate.base_price)
          .toFixed(2)
          .toString()
      );
    },
    getFflDeliveryCharges: function () {
      if (!this.$props.cart) return "FREE";
      if (!this.$props.cart.selected_ffl_shipping_rate) return "FREE";
      return (
        "$" +
        parseFloat(this.$props.cart.selected_ffl_shipping_rate.base_price)
          .toFixed(2)
          .toString()
      );
    },
    getTotal: function () {
      if (!this.$props.cart) return "";
      return (
        "$" + parseFloat(this.$props.cart.grand_total).toFixed(2).toString()
      );
    },
  },
};
</script>

<style scoped>
.theme-btn.active {
  transform: scale(1.1);
  transition: 1s all ease;
  vertical-align: baseline;
  margin-top: 7px;
}

.theme-btn.active:active,
.theme-btn.active:focus {
  transform: scale(1.1);
  transition: 1s all ease;
  vertical-align: baseline;
  margin-top: 7px;
  background-color: #fae31b !important;
  border: 2px solid #fae31b !important;
}

.icon.active {
  height: 50px;
  width: 50px;
  background-repeat: no-repeat;
  background-size: contain;
  -webkit-transition: width 1s ease-in-out;
  -moz-transition: width 1s ease-in-out;
  -o-transition: width 1s ease-in-out;
  transition: width 1s ease-in-out;
}
</style>
