<template>
    <div>
        <div class="my-5">
            <div class="container">
                <BuyAllNav @onCategoryChange="onCategoryChange"></BuyAllNav>
                <ProductList :products="products" :show-show-more-btn="showMore" @showMore="onShowMore" :isLoading="isLoading"></ProductList>
            </div>
        </div>

        <!-- <Map></Map> -->
    </div>
</template>

<script>
import BuyAllNav from "./BuyAllNav";
import ProductList from "./ProductList";
import Map from "./Map";
import {API_ENDPOINTS} from "../../constant";

export default {
    name: "BuyAll",
    components: {BuyAllNav, ProductList, Map},
    data: () => {
        return {
            products: [],
            paginationStart: 12,
            pagination: 0,
            category: 0,
            showMore: false,
            isLoading: false
        }
    },
    mounted() {
        this.pagination = this.paginationStart;
        this.loadProducts();
    },
    methods: {
        loadProducts() {
            this.isLoading = true
            let url = `${API_ENDPOINTS.getAllProducts}/${this.pagination}/${this.category}`
            this.$http.get(url).then(res => {
                this.isLoading = false;
                if (res.data.products.length == 0 || res.data.products.length < this.pagination) {
                    this.showMore = false
                } else {
                    this.showMore = true
                }
                this.pagination += 12;
                this.products = res.data.products;
            })
        },
        onShowMore() {
            this.loadProducts();
        },
        onCategoryChange(categoryId) {
            this.showMore = true
            this.category = categoryId
            this.pagination = this.paginationStart;
            this.loadProducts()
        },
    },
}
</script>

<style scoped>

</style>