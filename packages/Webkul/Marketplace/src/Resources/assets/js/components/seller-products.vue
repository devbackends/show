<template>
    <div class="list-group list-group-flush product-thumb__list">

        <div v-for="product in products" class="list-group-item product-thumb__list-item">
            <div class="row">
                <div class="col-md-5 product-thumb">
                    <div class="product-thumb__image">
                        <img v-if="product.seller.logo" :src="`${imagesPath}/${product.seller.logo}`" alt="Product image">
                        <img v-else :src="`${baseUrl}/images/product-thumb-empty.jpg`" alt="Product image">
                    </div>
                    <div class="product-thumb__info">
                        <a :href="product.shopUrl" class="name">{{product.seller.shop_title}}</a>
                        <div class="rate">
                            <span class="stars" v-html="getReviewHtml(product.review.average)"></span>
                            <span>{{product.review.average}} ({{product.review.total}} ratings)</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 labels">
                    <p class="text-gray-dark mb-0" v-if="typeof product.isWithinThirtyDays != 'undefined' && product.isWithinThirtyDays==1">Recently added</p>
                    <p class="h3 mb-0">{{product.price}}</p>
                    <p class="text-gray-dark font-weight-bold mb-0">{{product.condition.join(': ')}}</p>
                </div>
                <div class="col-md-4 text-md-right">
                    <add-to-cart
                        v-if="product.quantity > 0"
                        :csrf-token="token"
                        :product-flat-id="product.product_id"
                        :product-id="product.product_id"
                        :reload-page="false"
                        :move-to-cart="false"
                        :is-enable="product.isSaleable"
                        btn-text="Add To Cart"
                        ui="primary"
                        :seller-info="getSellerInfo(product)">
                    </add-to-cart>
                    <div v-else>
                        <button
                            type="submit"
                            disabled="true"
                            class="btn btn-outline-dark">
                            <i class="far fa-cart-plus"></i>
                            <span>Out Of Stock</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'seller-products',
    props: ['products', 'token', 'imagesPath'],
    methods: {
        getReviewHtml(review) {
            let html = ``;
            for (let i = 0; i < review; i++) {
                html += `<i class="fas fa-star"></i>`;
            }
            for (let i = 0; i < (5 - review); i++) {
                html += `<i class="fal fa-star"></i>`;
            }
            return html;
        },
        getSellerInfo(product) {
            if (!product.marketplace_seller_id) return null;
            return {
                productId: product.id,
                sellerId: product.marketplace_seller_id,
                isOwner: product.is_owner,
            }
        },
    },
}
</script>

<style scoped>

</style>