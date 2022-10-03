@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('content-wrapper')
    @guest('customer')
        <wishlist-product :is-customer="{{auth('customer')->user() ? 'true' : 'false'}}"></wishlist-product>
    @endguest

    @auth('customer')
        @push('scripts')
            <script>
                window.location = '{{ route('customer.wishlist.index') }}';
            </script>
        @endpush
    @endauth
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

                <shimmer-component v-if="!isProductListLoaded && !isMobile()" shimmer-count="6"></shimmer-component>

                <template v-else-if="isProductListLoaded && products.length > 0">
                    <carousel-component
                        slides-per-page="6"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="wishlist-products-carousel"
                        :slides-count="products.length">

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
            props: ['isCustomer'],

            data: () => ({
                products: [],
                isProductListLoaded: false,
            }),

            mounted: function () {
                this.getProductsList();
            },

            methods: {
                async getProductsList() {
                    let wishlistItems = this.getStorageValue('wishlist_product');

                    if (typeof wishlistItems !== 'object' || Object.keys(wishlistItems).length === 0) {
                        this.isProductListLoaded = true;
                        return false;
                    }

                    let data = [];
                    for (let seller in wishlistItems) {
                        for (let productId in wishlistItems[seller]) {
                            data.push({
                                product_id: productId,
                                marketplace_seller_id: seller,
                            })
                        }
                    }

                    let res = await this.$http.post('{{route('marketplace.wishlist.list')}}', {data: data})
                    if (res) {
                        this.products = res.data.products;
                    }
                    this.isProductListLoaded = true;
                },

                remove(productId) {
                    let wishlistItems = this.getStorageValue('wishlist_product');

                    if (productId == "all") {
                        wishlistItems = {};
                        this.products = {};
                    }

                    this.$root.headerWishlistCount++;
                    this.setStorageValue('wishlist_product', wishlistItems);

                    window.showAlert(
                        `alert-success`,
                        'Success',
                        `Items successfully removed`
                    );
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