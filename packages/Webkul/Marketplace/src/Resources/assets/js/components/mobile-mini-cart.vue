<template>
    <a :href="checkoutUrl" class="unset">
        <div class="badge-wrapper">
            <span class="badge">{{ itemsCount }}</span>
        </div>
        <i class="far fa-shopping-cart"></i>
    </a>
</template>

<script>
import {API_ENDPOINTS} from "../constant";

export default {
    props: [
        'checkoutUrl',
    ],

    data: function () {
        return {
            carts: [],
            cartsDetails: [],
            itemsCount: 0,

        };
    },

    mounted: function () {
        this.getCartDetails();
    },

    watch: {
        '$root.miniCartKey': function () {
            this.getCartDetails();
        },
    },

    methods: {
        getCartDetails() {
            this.$http.get(API_ENDPOINTS.cart.get)
                .then((response) => {
                    if (response.data.status) {
                        this.carts = response.data.data.carts;
                        this.cartsDetails = response.data.data.cartsDetails;
                        this.setCartItemsCount();
                    }
                }).catch((e) => {
                    console.log('Error');
                });
        },

        setCartItemsCount() {
            let count = 0;
            for (let key in this.carts) {
                for (let item of this.carts[key].items) {
                    count += item.quantity;
                }
            }
            this.itemsCount = count;
        },
    },
};
</script>
