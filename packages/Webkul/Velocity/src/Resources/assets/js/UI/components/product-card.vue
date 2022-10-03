<template>
    <div class="col-12 lg-card-container list-card product-card row" v-if="list">
        <div class="product-image">
            <a :title="product.name" :href="`${baseUrl}/product/${product.slug}`">
                <img
                    :src="product.image"
                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />

                <product-quick-view-btn :quick-view-details="product"></product-quick-view-btn>
            </a>
        </div>

        <div class="product-information">
            <div>
                <div class="product-name">
                    <a :href="`${baseUrl}/product/${product.slug}`" :title="product.name" class="unset">
                        <span class="paragraph black">{{ product.name }}</span>
                    </a>
                </div>

                <div class="product-price paragraph-big bold black" v-html="product.priceHTML"></div>

                <div class="product-rating" v-if="product.totalReviews && product.totalReviews > 0">
                    <star-ratings :ratings="product.avgRating"></star-ratings>
                    <span>{{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}</span>
                </div>

                <div class="product-rating" v-else>
                    <span class="fs14" v-text="product.firstReviewText"></span>
                </div>

                <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>
            </div>
        </div>
    </div>

    <div class="card grid-card product-card-new" v-else>

        <a :href="`${baseUrl}/product/${product.slug}`" :title="product.name" class="product-image-container">
            <img
                loading="lazy"
                :alt="product.name"
                :src="product.image"
                :data-src="product.image"
                class="card-img-top lzy_img"
                :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />
                <!-- :src="`${$root.baseUrl}/vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png`" /> -->

                 <product-quick-view-btn :quick-view-details="product"></product-quick-view-btn>

            <span v-if="product.new==1" class="new-product-flag">NEW</span>

        </a>



        <div class="card-body">

            <div class="product-name col-12 no-padding">
                <a
                    class="unset"
                    :title="product.name"
                    :href="`${baseUrl}/product/${product.slug}`">

                    <span class="paragraph black">{{ product.name }}</span>
                </a>
            </div>

            <div class="product-price paragraph-big bold black" v-html="product.priceHTML"></div>

            <div
                class="product-rating col-12 no-padding"
                v-if="product.totalReviews && product.totalReviews > 0">

                <star-ratings :ratings="product.avgRating"></star-ratings>
                <a class="product-rating__link" :href="`${$root.baseUrl}/reviews/${product.slug}`">
                    {{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}
                </a>
            </div>

            <div class="product-rating col-12 no-padding" v-else>
                <span class="fs14" v-text="product.firstReviewText"></span>
            </div>

            <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: [
            'list',
            'product',
        ],

        data: function () {
            return {
                'addToCart': 0,
                'addToCartHtml': '',
            }
        },
    }
</script>