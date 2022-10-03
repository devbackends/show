<template>
    <button
        id="quick-view-btn-container"
        class="btn btn-gray-darker product-item__overlay-quickview"
        @click="openQuickView({event: $event})">
        <i class="far fa-search-plus"></i>
        Quick View
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