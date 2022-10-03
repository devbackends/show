@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('page-detail-wrapper')

    <wishlist-product />

@endsection

@push('scripts')
    <script type="text/x-template" id="wishlist-product-template">
        <div class="settings-page">
            <div class="settings-page__header">
                <div class="settings-page__header-title">
                    <p>{{ __('shop::app.customer.account.wishlist.title') }}</p>
                </div>
                <div class="settings-page__header-actions">
                    <button
                        v-if="products.length > 0"
                        class="btn btn-outline-gray"
                        @click="remove('all')">
                        <i class="far fa-trash-alt"></i><span>{{ __('shop::app.customer.account.wishlist.deleteall') }}</span>
                    </button>
                </div>
            </div>
            <div class="settings-page__body">

                {!! view_render_event('bagisto.shop.customers.account.guest-customer.view.before') !!}


                <shimmer-component v-if="!isProductListLoaded && !isMobile()" shimmer-count="5" class="d-none d-lg-block"></shimmer-component>

                <shimmer-component v-if="!isProductListLoaded" shimmer-count="2" class="d-none d-sm-block d-lg-none"></shimmer-component>

                <shimmer-component v-if="!isProductListLoaded" shimmer-count="1" class="d-block d-sm-none"></shimmer-component>


                <template v-else-if="isProductListLoaded && products.length > 0">
                    <carousel-component
                        slides-per-page="5"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="wishlist-products-carousel"
                        :slides-count="products.length"
                        class="d-none d-lg-block">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in products">
                            <div class="product-list__item">
                                <product-card :product="product"></product-card>
                            </div>
                        </slide>
                    </carousel-component>
                    <carousel-component
                        slides-per-page="2"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="wishlist-products-carousel"
                        :slides-count="products.length"
                        class="d-none d-sm-block d-lg-none">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in products">
                            <div class="product-list__item">
                                <product-card :product="product"></product-card>
                            </div>
                        </slide>
                    </carousel-component>
                    <carousel-component
                        slides-per-page="1"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="wishlist-products-carousel"
                        :slides-count="products.length"
                        class="d-block d-sm-none">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in products">
                            <div class="product-list__item">
                                <product-card :product="product"></product-card>
                            </div>
                        </slide>
                    </carousel-component>
                </template>

                <span v-else-if="isProductListLoaded" class="guest-">{{ __('customer::app.wishlist.empty') }}</span>

                {!! view_render_event('bagisto.shop.customers.account.guest-customer.view.after') !!}

            </div>
        </div>

    </script>

    <script>
        Vue.component('wishlist-product', {
            template: '#wishlist-product-template',

            data: () => ({
                products: [],
                isProductListLoaded: false,
            }),

            mounted: function () {
                this.getProductsList();
            },

            methods: {
                async getProductsList() {
                    let res = await this.$http.post('{{route('marketplace.wishlist.list')}}')
                    if (res) {
                        this.products = res.data.products;
                    }
                    this.isProductListLoaded = true;
                },

                async remove(productId) {
                    let res = await this.$http.post('{{route('marketplace.wishlist.delete')}}', {
                        productId: productId,
                        marketplaceSellerId: 0,
                    })

                    if (res) {
                        this.products = [];
                        this.$root.headerWishlistCount++;
                        window.showAlert(
                            `alert-success`,
                            'Success',
                            `Items successfully removed`
                        );
                    }
                },
            },

            watch: {
                '$root.headerWishlistCount': function () {
                    this.getProductsList();
                }
            },
        });
    </script>
@endpush