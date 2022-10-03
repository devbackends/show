<template>
    <div class="shopping-cart py-5">
        <div class="container">
            <div class="shopping-cart__total" v-if="count.sellers">
                <i class="far fa-shopping-cart mr-2"></i>
                <p class="d-block d-md-inline-block">
                    You added <strong>{{ count.items }} item(s)</strong>
                    from <strong>{{ count.sellers }} {{(count.sellers > 1) ? 'different' : ''}} seller(s)</strong>
                </p>
            </div>

            <Cart v-for="(cart, index) in carts" :key="index" :cart="cart" @reload="reload"/>

            <div v-if="isNoCarts">
                Your cart is empty :(
            </div>
        </div>
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../constant";
import Cart from "./Cart";

export default {
    name: 'CartsWrapper',
    components: {Cart},
    data: () => ({
        carts: [],
        count: {
            sellers: 0,
            items: 0,
        },
        isNoCarts: false,
    }),
    mounted() {
        this.setCarts();
    },
    methods: {
        async setCarts() {
            let response = await this.$http.get(API_ENDPOINTS.cart.get);
            if (response.data.status) {
                this.carts = response.data.data.carts;

                if (this.carts.length === 0) {
                    this.count.sellers = 0;
                    this.count.items = 0;
                    this.isNoCarts = true;
                    return false;
                }

                // Count carts counts
                let itemsCount = 0;
                for (let cart of this.carts) {
                  if(cart.items.length > 0){
                    this.count.sellers+=1;
                  }
                    for (let item of cart.items) {
                        itemsCount += item.quantity;
                    }
                }

                this.count.items = itemsCount;
            }
        },
        async reload() {
            await this.setCarts();
            $('body').css('cursor', 'default')
            $('.shopping-cart__item').css('opacity', '1').css('pointer-events', 'auto')
        },
    },
}
</script>

<style scoped>

</style>