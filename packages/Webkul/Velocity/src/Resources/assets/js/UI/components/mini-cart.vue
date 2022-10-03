<template>
  <div
    :class="`dropdown header-middle__cart-button ${cartItems.length > 0 ? '' : 'disable-active'}`"
  >
    <cart-btn :item-count="cartItems.length"></cart-btn>

    <div
      id="cart-modal-content"
      v-if="cartItems.length > 0"
      class="cart-modal modal-content sensitive-modal cart-modal-content hide"
    >
      <!--Body-->
      <div class="cart-modal__items-list">
        <div class="row cart-modal__item" :key="index" v-for="(item, index) in cartItems">
          <div class="col-auto cart-modal__item-picture px-0">
            <a class="unset" :href="`${$root.baseUrl}/product/${item.product.url_key}`">
              <div
                class="product-image"
                :style="`background-image: url(${item.images.medium_image_url});`"
              ></div>
            </a>
          </div>
          <div class="col cart-modal__item-info-container">
            <div class="row">
              <div class="col-auto pr-0">
                <div class="display-inbl cart-modal__item-info-quantity">
                  <!-- <label class>{{ __('checkout.qty') }}</label> -->
                  <span>x</span>
                  <input type="text" disabled :value="item.quantity" class />
                </div>
              </div>
              <div class="col">
                <div class="cart-modal__item-info">
                  <div class="cart-modal__item-info-name">
                    <p v-html="item.name"></p>
                  </div>

                  <div class="cart-modal__item-info-price">
                    <p>{{ Number(item.base_total).toFixed(2) }}</p>
                  </div>
                </div>
              </div>
            </div>

            <a @click="removeProduct(item.id)" class="cart-modal__item-remove">
              <i class="far fa-trash-alt"></i>
            </a>
          </div>
        </div>
      </div>

      <!--Footer-->
      <div class="modal-footer__subtotal d-flex">
        <p class="modal-footer__subtotal-label mr-auto">{{ subtotalText }}</p>
        <p class="modal-footer__subtotal-price ml-auto">{{ cartInformation.base_sub_total }}</p>
      </div>

      <div class="modal-footer__actions d-flex">
        <a class="btn btn-link mr-auto" :href="viewCart">{{ cartText }}</a>
        <a :href="checkoutUrl" class="btn btn-primary ml-auto">{{ checkoutText }}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "cartText",
    "viewCart",
    "checkoutUrl",
    "checkoutText",
    "subtotalText",
  ],

  data: function () {
    return {
      cartItems: [],
      cartInformation: [],
    };
  },

  mounted: function () {
    this.getMiniCartDetails();
  },

  watch: {
    "$root.miniCartKey": function () {
      this.getMiniCartDetails();
    },
  },

  methods: {
    getMiniCartDetails: function () {
      this.$http
        .get(`${this.$root.baseUrl}/mini-cart`)
        .then((response) => {
          if (response.data.status) {
            this.cartItems = response.data.mini_cart.cart_items;
            this.cartInformation = response.data.mini_cart.cart_details;
          }
        })
        .catch((exception) => {
          console.log(this.__("error.something_went_wrong"));
        });
    },

    removeProduct: function (productId) {
      this.$http
        .delete(`${this.$root.baseUrl}/cart/remove/${productId}`)
        .then((response) => {
          this.cartItems = this.cartItems.filter(
            (item) => item.id != productId
          );

          window.showAlert(
            `alert-${response.data.status}`,
            response.data.label,
            response.data.message
          );
        })
        .catch((exception) => {
          console.log(this.__("error.something_went_wrong"));
        });
    },
  },
};
</script>
