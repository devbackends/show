<template>
  <div class="step-content" v-bind:class="!isActive ? 'disabled' : ''" id="shipping-section">
    <Accordian :disabled="!isActive" :title="'Shipping information'" :active="isActive">
      <div class="form-header" slot="header">
        <h3 class="display-inbl">Shipping information</h3>
      </div>
      <div :class="'shipping-methods'" slot="body">
        <template v-if="isFflExists">
          <p
            class="mb-4"
          >Certain items in your cart must be delivered to a Federal Firearms License (FFL) holder. Please select an FFL for delivery, or select pickup directly from our store.</p>
          <div class="row mb-4">
            <div class="col-sm-12">
              <h4 class="product-type">Firearms</h4>
            </div>
            <div class="col-sm-12" v-for="item in fflItems">
              <p class="product-name">{{item.product.name}}</p>
            </div>
            <div class="col-sm-12 d-flex" v-if="!selectedFflShipping">
              <div class="mr-auto">
                <button
                  id="select-an-ffl-delivery-button"
                  data-toggle="modal"
                  data-target=".bd-example-modal-xl"
                  class="btn btn-outline-primary"
                >Select an FFL for delivery</button>
              </div>
              <div class="ml-auto">
                <button
                  v-on:click="onFflPickUpSeller"
                  id="pick-up-from-our-store-button"
                  class="btn btn-outline-primary"
                >Or pick up from seller</button>
              </div>
            </div>
            <div v-if="selectedFflShipping" class="col-sm-12">
              <p>This item will be delivered to:</p>
              <p class="mb-0">
                <strong>{{selectedFflShipping.company_name}}</strong>
              </p>
              <p class="mb-0">{{selectedFflShipping.street_address}}</p>
              <p>{{selectedFflShipping.city}}, {{selectedFflShipping.state}} {{selectedFflShipping.zip_code}}</p>

              <button
                data-toggle="modal"
                data-target=".bd-example-modal-xl"
                class="btn btn-outline-dark btn-sm"
              >Change delivery location</button>
            </div>
            <div class="col-md-12 mt-3 shipping-methods__options">
              <div>
                <template v-for="rateGroup in shippingRates">
                  <div  v-for="rate in rateGroup.rates">

                      <div class="form-group">
                        <div class="radio-button">
                          <div class="radio-button__input">
                            <input
                          checked
                          v-validate="'required'"
                          type="radio"
                          v-model="fflItemsShippingRateSelected"
                          :value="rate.method"
                          :id="rate.method"
                          name="shipping_method_ffl"
                        />
                            <label></label>
                          </div>
                          <div class="radio-button__label">
                            <p>{{ rate.method_title }}</p>
                            <p>${{ Math.round(rate.formated_base_price * 100) / 100 }}</p>
                          </div>
                        </div>
                      </div>

                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>
        <template v-if="otherItems.length > 0">
          <div class="row">
            <div class="col-sm-12">
              <h4 class="product-type">Other products</h4>
            </div>
            <div class="col-sm-12" v-for="item in otherItems">
              <p class="product-name">{{item.product.name}}</p>
            </div>
            <div class="col-sm-12 d-flex" v-if="!selectedOtherShipping">
              <div class="mr-auto">
                <button
                  data-toggle="modal"
                  data-target="#newsShippingPopup"
                  id="select-an-not-ffl-delivery-button"
                  class="btn btn-outline-primary shipping-btn"
                >Select shipping address</button>
              </div>
              <div class="ml-auto">
                <button
                  v-on:click="onOtherPickUpSeller"
                  id="pick-up-from-not-ffl-our-store-button"
                  class="btn btn-outline-primary shipping-btn"
                >Or pick up from seller</button>
              </div>
            </div>
            <div v-if="selectedOtherShipping" class="col-sm-12">
              <p>This item will be delivered to:</p>
              <p class="mb-0">
                <strong>{{selectedOtherShipping.first_name}} {{selectedOtherShipping.last_name}}</strong>
              </p>
              <p class="mb-0">{{selectedOtherShipping.address1[0]}}</p>
              <p>{{selectedOtherShipping.city}}, {{selectedOtherShipping.state}} {{selectedOtherShipping.postcode}}</p>
              <button
                data-toggle="modal"
                data-target="#newsShippingPopup"
                class="btn btn-outline-dark btn-sm"
              >
                Change
                shipping address
              </button>
            </div>
            <div class="col-md-12 mt-3 shipping-methods__options">
              <div>
                <template v-for="rateGroup in shippingRates">
                  <div v-for="rate in rateGroup.rates">
                      <div class="form-group">
                        <div class="radio-button">
                          <div class="radio-button__input">
                            <input
                              checked
                              v-validate="'required'"
                              type="radio"
                              v-model="otherItemsShippingRateSelected"
                              :id="rate.method"
                              name="shipping_method_other"
                              :value="rate.method"
                            />
                            <label></label>
                          </div>
                          <div class="radio-button__label">
                            <p>{{ rate.method_title }}</p>
                            <p>${{ Math.round(rate.formated_base_price * 100) / 100 }}</p>
                          </div>
                        </div>
                      </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>
      </div>
    </Accordian>
    <ffl-popup :billingAddr="billingAddress" v-on:change="onSelectedFfl"></ffl-popup>
    <address-popup :el-id="'newsShippingPopup'" v-on:change="onSelectedOtherShipping"></address-popup>
  </div>
</template>

<script>
import Accordian from "./Accordian";
import { API_ENDPOINTS, FIREARM_FAMILY } from "../../constant";
import FflPopup from "./FflPopup";
import AddressPopup from "./AddressPopup";

export default {
  name: "Shipping",
  components: {
    Accordian,
    FflPopup,
    AddressPopup,
  },
  props: {
    isActive: Boolean,
    cartItems: {
      type: Array,
      default: [],
    },
    cart: {
      type: Object,
    },
    shipToBillingAddress: [Object, null],
  },
  data: function () {
    return {
      fflItems: [],
      otherItems: [],
      shippingRates: [],
      selectedFflShipping: null,
      selectedOtherShipping: null,
      otherItemsShippingRateSelected: null,
      fflItemsShippingRateSelected: null,
      billingAddress: null,
    };
  },
  methods: {
    getShippingRates: function () {
      if (this.shippingRates.length > 0) {
        return;
      }
      this.$http
        .get(API_ENDPOINTS.getShippingRates)
        .then((res) => {
          this.shippingRates = res.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onSelectedFfl: function (ffl) {
      this.selectedFflShipping = ffl;
      this.getShippingRates();
    },
    onSelectedOtherShipping: function (addr) {
      this.selectedOtherShipping = addr;
      this.getShippingRates();
    },
    onFflPickUpSeller: function () {},
    onOtherPickUpSeller: function () {
      this.$http
        .get(API_ENDPOINTS.getCompanyAddress)
        .then((res) => {
          console.log(res);
          this.onSelectedOtherShipping({
            city: res.data.data.city,
            country: "US",
            state: res.data.data.state,
            postcode: res.data.data.zip_code,
            address1: [res.data.data.address1],
            email: res.data.data.email,
            first_name: res.data.data.name,
            last_name: res.data.data.name,
            phone: res.data.data.phone,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  computed: {
    isFflExists: function () {
      return this.fflItems.length > 0;
    },
  },
  mounted() {
    eventBus.$on("shippingChanged", () => this.getShippingRates());
  },
  watch: {
    cart: function () {
      if (!this.cart) return;
      if (this.cart.ffl_shipping_method) {
        if (
          this.cart.ffl_shipping_method !== this.fflItemsShippingRateSelected
        ) {
          this.fflItemsShippingRateSelected = this.cart.ffl_shipping_method;
        }
      }
      if (this.cart.shipping_method) {
        if (this.cart.shipping_method !== this.otherItemsShippingRateSelected) {
          this.otherItemsShippingRateSelected = this.cart.shipping_method;
        }
      }
      if (this.cart.shipping_address && !this.selectedOtherShipping) {
        this.selectedOtherShipping = this.cart.shipping_address;
      }
      if (this.cart.ffl_address && !this.selectedFflShipping) {
        this.$http
          .get(API_ENDPOINTS.getFflById + "/" + this.cart.ffl_address.ffl_id)
          .then((res) => {
            this.selectedFflShipping = res.data.business_info;
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
    cartItems: function (items) {
      this.fflItems = [];
      this.otherItems = [];
      items.forEach((item) => {
        if (
          item.product.attribute_family.code.toLowerCase() === FIREARM_FAMILY
        ) {
          this.fflItems.push(item);
        } else {
          this.otherItems.push(item);
        }
      });
    },
    shipToBillingAddress: function (shipToBillingAddress) {
      this.selectedOtherShipping = shipToBillingAddress;
    },
    selectedFflShipping: function () {
      this.$emit("changeFflShipping", this.selectedFflShipping);
    },
    selectedOtherShipping: function () {
      this.$emit("changeOtherShipping", this.selectedOtherShipping);
    },
    fflItemsShippingRateSelected: function () {
      this.$emit("changeFflShippingRate", this.fflItemsShippingRateSelected);
    },
    otherItemsShippingRateSelected: function () {
      this.$emit(
        "changeOtherItemShippingRate",
        this.otherItemsShippingRateSelected
      );
    },
  },
};
</script>
