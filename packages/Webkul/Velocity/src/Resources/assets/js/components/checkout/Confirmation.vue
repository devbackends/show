<template xmlns="http://www.w3.org/1999/html">
  <div class="step-content" v-bind:class="!isActive ? 'disabled' : ''" id="confirmation-section">
    <div class="form-container">
      <Accordian :disabled="!isActive" :title="'Confirmation'" :active="isActive">
        <div class="form-header" slot="header">
          <h3 class="display-inbl">Confirmation</h3>
        </div>
        <div :class="'confirmation'" slot="body">
          <template v-if="isFflExists">
            <h4 class="heading">Firearms</h4>
            <div class="row mb-3">
              <div class="col-md-7 pr-0">
                <div class="products-container">
                  <div class="top-products-border"></div>
                  <div class="bottom-products-border"></div>
                  <template v-for="item in fflItems">
                    <div class="row product-container">
                      <div class="col-auto pr-0">
                        <img
                          :src="item.product.base_image.small_image_url"
                          class="checkout__form-confirmation-content-image"
                        />
                      </div>
                      <div class="col">
                        <div class="row checkout__form-confirmation-content-products">
                          <div class="col-auto pr-0">
                            <p>{{item.quantity}} x</p>
                          </div>
                          <div class="col">
                            <p>{{item.product.name}}</p>
                            <p>${{item.total}}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
              <div class="col-md-5">
                <div v-if="shipping.selectedFflShipping">
                  <p>
                    Your firearm will be delivered to the
                    following FFL:
                  </p>
                  <p class="checkout__form-confirmation-shipping-name">{{shipping.selectedFflShipping.company_name}}</p>
                  <p class="mb-0">
                    {{shipping.selectedFflShipping.street_address}},
                    {{shipping.selectedFflShipping.city}}, {{shipping.selectedFflShipping.zip_code}}
                  </p>
                  <p>( {{Number(shipping.selectedFflShipping.distance).toFixed(1) }} mi. )</p>
                  <!--                                <p>{{shipping.fflItemsShippingRateSelected}}</p>-->
                </div>
                <div class="delivery-location-container">
                  <button
                    data-toggle="modal"
                    data-target=".bd-example-modal-xl"
                    class="btn btn-outline-dark btn-sm"
                  >
                    Change
                    delivery
                    location
                  </button>
                </div>
              </div>
            </div>
          </template>
          <h4 class="heading">Other products</h4>
          <div class="row mb-3">
            <div class="col-md-7 pr-0">
              <div class="products-container">
                <div class="top-products-border"></div>
                <div class="bottom-products-border"></div>
                <template v-for="item in otherItems">
                  <div class="row product-container">
                    <div class="product-image-container col-auto pr-0">
                      <img
                        :src="item.product.base_image.small_image_url"
                        class="checkout__form-confirmation-content-image"
                      />
                    </div>
                    <div class="col">
                      <div class="row checkout__form-confirmation-content-products">
                        <div class="col-auto pr-0">
                          <p>{{item.quantity}} x</p>
                        </div>
                        <div class="col">
                          <p>{{item.product.name}}</p>
                          <p>${{item.total}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <div class="col-md-5">
              <div v-if="shipping.selectedOtherShipping">
                <p>These items will be delivered to:</p>
                <p class="checkout__form-confirmation-shipping-name">
                  {{shipping.selectedOtherShipping.first_name}}
                  {{shipping.selectedOtherShipping.last_name}}
                </p>
                <p>
                  {{shipping.selectedOtherShipping.address1[0]}},
                  {{shipping.selectedOtherShipping.city}}, {{shipping.selectedOtherShipping.postcode}}
                </p>
                <!--                            <p>{{shipping.fflItemsShippingRateSelected}}</p>-->
              </div>
              <div class="delivery-location-container">
                <button
                  data-toggle="modal"
                  data-target="#newsShippingPopup"
                  class="btn btn-outline-dark btn-sm"
                >
                  Change
                  shipping address
                </button>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
                <div class="radio-button checkbox-button">
                  <div class="radio-button__input">
                    <input
                      ref="confirmationRadio"
                      v-model="confirmed"
                      type="checkbox"
                      id
                      name="confirmed"
                      value
                      class
                    />
                    <label v-on:click="onConfirmationClick"></label>
                  </div>
                  <div
                    class="radio-button__label"
                  >Accept terms and conditions before placing the order</div>
                </div>
            </div>
            <div class="col-12">
                            <ul class="checkout__form-confirmation-conditions">
              <li>Are at least 21 years of age</li>
              <li>Have no felony convictions</li>
              <li>Are in compliance with applicable state and federal regulations</li>
              <li>
                Are not prohibited from legally purchasing or possessing firearms and/or other products in
                your cart according to local, state, and federal laws
              </li>
              <li>Agree to 2AGunShow's Privacy Policy and Terms and Conditions</li>
            </ul>
            </div>
          </div>
        </div>
      </Accordian>
    </div>
  </div>
</template>

<script>
import Accordian from "./Accordian";
import { FIREARM_FAMILY } from "../../constant";

export default {
  name: "Confirmation",
  components: {
    Accordian,
  },
  props: {
    isActive: Boolean,
    cartItems: {
      type: Array,
      default: [],
    },
    shipping: [Object, null],
  },
  data: function () {
    return {
      fflItems: [],
      otherItems: [],
      confirmed: false,
    };
  },
  methods: {
    onConfirmationClick: function () {
      this.$refs.confirmationRadio.click();
    },
  },
  mounted() {},
  watch: {
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
    confirmed: function () {
      this.$emit("change", this.confirmed);
    },
  },
  computed: {
    isFflExists: function () {
      return this.fflItems.length > 0;
    },
  },
};
</script>

<style scoped>
.product-image-container img {
  max-width: 75%;
}

.heading {
  text-transform: uppercase;
}

.quantity-container {
  max-width: initial;
  float: left;
  line-height: 70px;
  padding-right: 10px;
}

.accept-shipping-confirmation-container {
  text-align: left;
  padding-bottom: 0;
}

span.after:before {
  display: block;
  overflow: hidden;
  margin-left: 2rem;
  content: ">";
  float: left;
  margin-right: 1rem;
}

span.after {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 23px;
}
</style>
