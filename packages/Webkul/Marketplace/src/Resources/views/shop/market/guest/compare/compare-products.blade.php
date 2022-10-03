@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <div class="settings-page">
            <div class="settings-page__header">
                <div class="settings-page__header-title">
                    <p>Compare</p>
                </div>
                <div class="settings-page__header-actions"></div>
            </div>
            <div class="settings-page__body">

                <shimmer-component v-if="!isProductListLoaded && !isMobile()"></shimmer-component>
                <div v-if="isProductListLoaded && products.length > 0" class="compare-products">

                    <div class="compare-products__head">
                        <span class="name">Product</span>
                        <span class="image">Image</span>
                        <span class="condition">Condition</span>
                        <span class="price">Price</span>
                        <span class="action"></span>
                    </div>
                    <div class="compare-products__list">
                        <carousel-component
                            slides-per-page="3"
                            navigation-enabled="true"
                            pagination-enabled="hide"
                            id="compare-products-carousel"
                            :slides-count="products.length">
                            <slide
                                :key="index"
                                :slot="`slide-${index}`"
                                v-for="(product, index) in products">
                                <div class="compare-products__list-item">
                                    <div class="name">
                                        <a :href="product.link" class="h5" v-text="product.name"></a>
                                    </div>
                                    <div class="image">
                                        <a :href="product.link">
                                            <img :src="product.image" class="image-fluid">
                                        </a>
                                    </div>
                                    <div class="condition">
                                        <span class="badge badge-dark">@{{product.condition}}</span>
                                    </div>
                                    <div class="price">
                                        <div v-html="product.price"></div>
                                    </div>
                                    <div class="action">
                                            <add-to-cart
                                            ui="primary"
                                            btn-text="{{ __('shop::app.products.add-to-cart') }}"
                                            csrf-token='{{ csrf_token() }}'
                                            :product-flat-id="product.id"
                                            :product-id="product.product_id"
                                            reload-page="1"
                                            move-to-cart="false"
                                            is-enable="true"
                                            :seller-info="getSellerInfo(product)">
                                        </add-to-cart>
                                        <div class="links">

                                            <a class="" :href="`${$root.baseUrl}/customer/wishlist/add/`+product.product_id" title="text">
                                            <wishlist-component
                                                :active="false"
                                                :is-customer="isCustomer"
                                                :product-id="product.product_id"
                                                :seller-id="product.marketplace_seller_id ? product.marketplace_seller_id : 0"
                                                add-url="{{route('marketplace.wishlist.add')}}"
                                                remove-url="{{route('marketplace.wishlist.delete')}}"
                                                linkTitle="{{__('velocity::app.shop.wishlist.add-wishlist-text')}}"
                                            ></wishlist-component>
                                            </a>
                                            <a @click="removeProduct(product.product_id, product.marketplace_seller_id)">
                                                <i class="far fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </slide>
                        </carousel-component>
                    </div>
                </div>
                <span v-else-if="isProductListLoaded && products.length == 0">
                        Compare products is empty
                </span>
            </div>
        </div>
    </script>

    <script>
        Vue.component('compare-product', {
            template: '#compare-product-template',

            data: function () {
                return {
                    products: [],
                    isProductListLoaded: false,
                    isCustomer: {{ auth()->guard('customer')->user() ? 'true' : 'false' }},
                }
            },

            mounted: function () {
                if (this.isCustomer) {
                    this.getCustomerComparedProducts();
                } else {
                    this.getGuestComparedProducts();
                }
            },

            methods: {
                getCustomerComparedProducts() {
                    this.$http.post('{{route('marketplace.compare.list')}}').then(response => {
                        this.isProductListLoaded = true;
                        this.products = response.data.products;
                    }).catch(error => {
                        this.isProductListLoaded = true;
                        console.log(this.__('error.something_went_wrong'));
                    });
                },

                getGuestComparedProducts() {
                    let comparedItems = this.getStorageValue('compared_product');
                    let data = [];
                    if (typeof comparedItems === 'object') {
                        for (let seller in comparedItems) {
                            for (let productId in comparedItems[seller]) {
                                data.push({
                                    product_id: productId,
                                    marketplace_seller_id: seller
                                });
                            }
                        }
                    }
                    this.$http.post('{{route('marketplace.compare.list')}}', {data: data}).then(response => {
                        this.isProductListLoaded = true;
                        this.products = response.data.products;
                    }).catch(error => {
                        this.isProductListLoaded = true;
                        console.log(this.__('error.something_went_wrong'));
                    });
                },

                removeProduct(productId, seller) {
                    if (this.isCustomer) {
                        this.removeCustomerProduct(productId, seller)
                    } else {
                        this.removeGuestProduct(productId, seller)
                    }
                },

                removeCustomerProduct(productId, seller) {
                    this.$http.post('{{route('marketplace.compare.delete')}}', {
                        productId: productId,
                        marketplaceSellerId: seller,
                        _token: '{{csrf_token()}}'
                    }).then(response => {
                        this.$root.headerCompareCount++;
                        this.products = this.products.filter(product => {
                            return !(product.product_id === productId && product.marketplace_seller_id === seller)
                        })
                        window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                    }).catch(error => {
                        console.log(this.__('error.something_went_wrong'));
                    });
                },

                removeGuestProduct(productId, seller) {
                    let compareItems = this.getStorageValue('compared_product');

                    if (compareItems[seller] && compareItems[seller][productId]) {
                        delete compareItems[seller][productId]
                    }

                    this.setStorageValue('compared_product', compareItems);

                    this.products = this.products.filter(product => {
                        return !(product.product_id === productId && product.marketplace_seller_id === seller)
                    })
                    this.$root.headerCompareCount++;
                    window.showAlert(
                        `alert-success`,
                        'Success',
                        `Item succesfully removed`
                    );
                },
                getSellerInfo(product) {

                        let options = {
                            sellerId: product.marketplace_seller_id,
                        }
                        if (product.marketplaceProduct) {
                            options.productId = product.marketplaceProduct.id;
                            options.isOwner = product.marketplaceProduct.is_owner;
                        } else {
                            options.productId = product.id;
                            options.isOwner = 1;
                        }
                        return options;

                    return null;
                }
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            var scroll = 0;

            $(document).on('click', '#scroll-left', function (e) {
                if (scroll >= 200) {
                    scroll -= 200;
                    var leftPos = $('.compare-products').scrollLeft(scroll);
                    /*$(".compare-products").animate({scrollLeft: scroll}, 800);*/
                }

            });
            $(document).on('click', '#scroll-right', function (e) {

                if (scroll < $('.compare-products').width() + 160) {
                    scroll += 200;
                    var rightPos = $('.compare-products').scrollLeft(scroll);
                    /*$(".compare-products").animate({scrollLeft: scroll}, 800);*/
                }


            });
        });
    </script>
@endpush