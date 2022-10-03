<template>
    <div class="row d-flex align-items-center product-item product-item__list-item py-1" :class="[!internalProduct.isInStock ? 'product-item__out-of-stock' : '' ]" v-if="list">
        <div class="col-auto">
            <a :title="internalProduct.name" :href="`${baseUrl}/product/${internalProduct.slug}`" class="product-item__list-item-link">
                <img :src="internalProduct.image" alt="image" :data-src="internalProduct.image">
            </a>
        </div>
        <div class="col px-0">
            <div class="product-item__body card-body">
                <a :href="`${baseUrl}/product/${internalProduct.slug}`">
                    <p class="name">{{ internalProduct.name }}</p>
                </a>
                <a v-if="internalProduct.seller" :href="`${baseUrl}/${internalProduct.seller.url}`">
                    <p class="name">{{ internalProduct.seller.shop_title }}</p>
                </a>
            </div>
        </div>

        <div class="col-auto">
            <span class="price" v-html="internalProduct.priceHTML"></span>
        </div>
        <div class="col-auto">
            <vnode-injector :nodes="getDynamicHTML(internalProduct.addToCartHtml)"></vnode-injector>
        </div>
    </div>

    <div class="product-item card" :class="[!internalProduct.isInStock ? 'product-item__out-of-stock' : '' ]" v-else>
        <div class="product-item__image card-img-top">
            <a :title="internalProduct.name" :href="`${baseUrl}/product/${internalProduct.slug}`">
                <img :src="internalProduct.image" alt="image" :data-src="internalProduct.image">
                <span v-if="internalProduct.condition" class="badge badge-dark">{{ internalProduct.condition }}</span>
                <span v-if="typeof internalProduct.isWithinThirtyDays != 'undefined' && internalProduct.isWithinThirtyDays==1" class="badge badge-dark">New</span>
            </a>
        </div>
        <div class="product-item__sold-out-label" v-if="!internalProduct.isInStock">
            <span class="badge badge-info-dark">Sold Out</span>
        </div>
        <div class="product-item__body card-body">
            <a :href="`${baseUrl}/product/${internalProduct.slug}`">
                <p class="name mb-1">{{ internalProduct.name }}</p>
            </a>
            <p v-if="internalProduct.seller" class="seller-name mb-1 text-gray">Sold by {{ internalProduct.seller.shop_title }}</p>
            <span class="price" v-html="internalProduct.priceHTML"></span>
            <div class="product-item__free-shipping" v-if="internalProduct.isFreeShipping">
                <i class="fas fa-shipping-fast"></i><span>Free Shipping</span>
            </div>
        </div>

        <vnode-injector v-if="internalProduct.type!='booking' &&  internalProduct.addToCartHtml" :nodes="getDynamicHTML(internalProduct.addToCartHtml)"></vnode-injector>
        <div v-if="internalProduct.type=='booking'">
            <div class="product-item__action" is-enable="true">
                <a style="width:100%;" :href="`/product/${internalProduct.url}`">
                    <button type="button" id="" class="submit-button btn btn-link small-padding btn- ">
                        <span class=""><i class="submit-button__icon--left far fa-cart-plus"></i>Register</span>
                    </button>
                </a>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: [
        'list',
        'product',
    ],

    data: () => ({
        internalProduct: {}
    }),

    mounted() {
        this.internalProduct = this.product;

        if (!this.internalProduct.slug && this.internalProduct.url_key) {
            this.internalProduct.slug = this.internalProduct.url_key;
        }
        if (!this.internalProduct.priceHTML && this.internalProduct.price) {
            this.internalProduct.priceHTML = this.internalProduct.price;
        }
    },
}
</script>