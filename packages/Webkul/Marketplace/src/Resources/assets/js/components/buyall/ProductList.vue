<template>
    <div>
        <div class="row mx-n1">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item__card px-1 mb-2" v-for="(product, index) in products" :key="index">
                <product-card :product="product"></product-card>
            </div>
        </div>
        <shimmer-component v-if="isLoading" shimmer-count="4"></shimmer-component>
        <div class="row" v-if="showShowMoreBtn && !isLoading">
            <div class="col-12 text-center mt-5" @click="showMore">
                <a class="btn btn-outline-gray">Show more</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProductList',
    props: ['products', 'showShowMoreBtn', 'isLoading'],
    methods: {
        showMore() {
            this.$emit('showMore')
        },
    },
}
</script>

<style scoped>

</style>