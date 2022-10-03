<template>
    <button type="button"
            id="mini-cart"
            class="btn btn-link disable-box-shadow cursor-not-allowed"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            :class="`btn btn-link ${itemCount == 0 ? 'cursor-not-allowed' : ''}`">
        <div class="mini-cart-content">
            <i class="far fa-shopping-cart"></i>
            <span class="badge" v-text="itemCount" v-if="itemCount != 0"></span>
            <span class="cart-text">{{text}}</span>
        </div>
        <div class="down-arrow-container">
            <span class="fal fa-angle-down"></span>
        </div>
    </button>
</template>

<script>
    export default {
        name: "cart-btn",
        props: ['itemCount', 'text'],
    }
</script>

<style scoped>

</style>