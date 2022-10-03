<template>
    <button
        id="quick-view-btn-container"
        class="btn btn-dark quick-view-btn-container"
        @click="openQuickView({event: $event})">
        <i class="far fa-search-plus"></i>
        {{ __('products.quick-view') }}
    </button>
</template>

<script type="text/javascript">
    export default {
        props: ['quickViewDetails'],

        methods: {
            openQuickView: function ({event}) {
                event.preventDefault();
                event.stopPropagation();

                this.$root.quickView = true;
                this.$root.productDetails = this.quickViewDetails;

                $('body').toggleClass('overflow-hidden');
            },
        }
    }
</script>