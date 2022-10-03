<template>
    <div v-if="cart.items.length > 0" class="card shopping-cart__item mb-4" :id="`cart-${cart.id}`">
       
         <div class="card-header">
           Sold by <a :href="`/${cart.seller.url}`" class="font-weight-bold">{{cart.seller.shop_title}}</a>
           <span v-if="(cart.seller.type === 'plus' || cart.seller.type === 'god') && cart.seller.parsed_payment_methods.filter((method) => method.code === 'fluid').length > 0" class="badge badge-light ml-sm-3"><i class="far fa-credit-card mr-2"></i>This seller accepts credit cards</span>
         </div>
         <div class="card-body">
           <div class="row">
             <div class="col-12 col-md-8">

               <CartItem v-for="(item, index) in cart.items" :key="index" :item="item"
                         @remove="removeItem" @updateQuantity="updateQuantity"/>
             </div>
             <div class="col-12 col-md-4">
               <div class="shopping-cart__summary">
                 <div>
                   <p class="font-weight-bold mb-2">Accepted payment methods</p>
                   <div v-if="(cart.seller.type === 'plus' || cart.seller.type === 'god') && cart.seller.parsed_payment_methods.filter((method) => method.code === 'fluid').length > 0"  class="shopping-cart__summary-cards">
                     <img src="/themes/market/assets/images/cart-credit-card-visa.png" alt="Visa">
                     <img src="/themes/market/assets/images/cart-credit-card-master.png" alt="Mastercard">
                     <img src="/themes/market/assets/images/cart-credit-card-american.png" alt="American Express">
                   </div>
                   <p v-for="(method, index) in cart.seller.parsed_payment_methods.filter((method) => method.code !== 'fluid')" :key="index">
                     {{method.title}}
                   </p>
                 </div>
                 <div class="shopping-cart__summary-info">
                   <div class="shopping-cart__summary-info-item">
                     <p>{{ itemsCount }} item(s) price</p>
                     <p>${{ parseFloat(cart.base_sub_total).toFixed(2) }}</p>
                   </div>
                   <div class="shopping-cart__summary-info-item">
                     <p>Shipping charges</p>
                     <p v-if="getOtherDeliveryCharges" v-text="getOtherDeliveryCharges"></p>
                   </div>
                   <div v-if="cart.discount_amount > 0" class="shopping-cart__summary-info-item">
                     <p>Discount Amount</p>
                     <p  v-text="parseFloat(cart.base_discount_amount).toFixed(2)"></p>
                   </div>
                   <!--                            <div class="shopping-cart__summary-info-item">
                                                   <p>Tax 0%</p>
                                                   <p>${{ parseFloat(cart.base_tax_total).toFixed(2) }}</p>
                                               </div>-->
                   <div class="shopping-cart__summary-info-item font-weight-bold">
                     <p>Grand Total</p>
                     <p>${{ parseFloat(cart.base_grand_total).toFixed(2) }}</p>
                   </div>
                 </div>

                 <Coupon  @onApplyCoupon="onApplyCoupon" :cart="cart"></Coupon>
                 <a :href="checkoutUrl">
                   <button type="button" class="btn btn-primary btn-block justify-content-center">Proceed To Checkout</button>
                 </a>
               </div>
             </div>
           </div>
         </div>


    </div>
</template>

<script>
import CartItem from "./CartItem";
import Coupon from "../coupon/coupon"
import {API_ENDPOINTS} from "../../constant";
export default {
    name: 'Cart',
    components: {CartItem,Coupon},
    props: ['cart'],
    data: () => ({
        itemsCount: 0,
    }),
    computed: {
        checkoutUrl() {
            return API_ENDPOINTS.checkout.replace('{sellerId}', this.cart.seller_id)
        },
        getOtherDeliveryCharges: function () {
            const cart = this.cart;
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
            const cart = this.cart;
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
        }
    },
    mounted() {
        this.countItems();
    },
    methods: {
        async removeItem(itemId) {
            const body = $('body'),
                cartEl = $(`#cart-${this.cart.id}`);

            body.css('cursor', 'wait')
            cartEl.css('opacity', '0.5').css('pointer-events', 'none');

            const response = await this.$http.delete(API_ENDPOINTS.cart.remove
                .replace('{sellerId}', this.cart.seller_id)
                .replace('{itemId}', itemId)
            );

            if (response.data.status) {
                this.$emit('reload');
            } else {
                body.css('cursor', 'default')
                cartEl.css('opacity', '1').css('pointer-events', 'auto');
            }
        },
        async updateQuantity(options) {
            const body = $('body'),
                cartEl = $(`#cart-${this.cart.id}`);

            // Get item
            let item;
            for (let cartItem of this.cart.items) {
                if (cartItem.id === options.id) item = cartItem;
            }

            // Check if quantity have been changed
            if (item.quantity === options.quantity) return false;

            body.css('cursor', 'wait')
            cartEl.css('opacity', '0.5').css('pointer-events', 'none');

            const response = await this.$http.post(API_ENDPOINTS.cart.update, {
                itemId: options.id,
                data: {
                    quantity: options.quantity
                }
            });

            if (response.data.status) {
                this.$emit('reload');
            } else {
                body.css('cursor', 'default')
                cartEl.css('opacity', '1').css('pointer-events', 'auto');
            }
        },
        countItems() {
            let count = 0;
            for (let item of this.cart.items) {
                count += item.quantity;
            }
            this.itemsCount = count;
        },
        onApplyCoupon(){
            this.$emit('reload');
        }
    },
    watch: {
        cart() {
            this.countItems();
        }
    }
}
</script>

<style scoped>

</style>