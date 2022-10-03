<script type="text/x-template" id="searchbar-template">
    <div class="row no-margin right searchbar">
        <div class="col-7 no-padding input-group">
            <site-search
                search-url="{{ route('shop.search.index') }}"
                show-url="{{ route('marketplace.shows.index') }}"
                instructor-url="{{ route('marketplace.instructors.index') }}"
                gunrange-url="{{ route('marketplace.gun-ranges.index') }}"
                ffl-url="{{ route('marketplace.ffl.index') }}"
                club-url="{{ route('marketplace.clubs.index') }}"
            ></site-search>
        </div>
        <div class="col-5">
            {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}
            <a class="btn btn-link wishlist-btn"
               :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                <i class="far fa-heart"></i>
                <span class="badge" v-text="wishlistCount" v-if="wishlistCount > 0"></span>
                <span>{{ __('shop::app.layouts.wishlist') }}</span>
            </a>
            {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}
            <a class="btn btn-link compare-btn"
               @auth('customer')
               href="{{ route('velocity.customer.product.compare') }}"
               @endauth

               @guest('customer')
               href="{{ route('velocity.product.compare') }}"
                @endguest
            >
                <i class="far fa-compress-alt"></i>
                <span class="badge" v-text="compareCount" v-if="compareCount > 0"></span>
                <span>{{ __('velocity::app.customer.compare.text') }}</span>
            </a>
            {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}
            <mini-cart
                cart-button-text="{{ __('velocity::app.minicart.cart') }}"
                view-cart="{{ route('marketplace.cart.view') }}"
                cart-text="{{ __('shop::app.minicart.view-cart') }}"
                checkout-text="{{ __('shop::app.minicart.checkout') }}"
                subtotal-text="{{ __('shop::app.checkout.cart.cart-subtotal') }}">
            </mini-cart>
            {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
        </div>
    </div>
</script>

<script type="text/x-template" id="quantity-changer-template">
    <div class="" :class="`input-group quantity ${errors.has(controlName) ? 'has-error' : ''}`">
    <!-- <label class="required">{{ __('shop::app.products.quantity') }}</label> -->
        <div class="input-group-prepend">
            <button type="button" class="btn btn-outline-light" @click="decreaseQty()"><i class="fal fa-minus"></i>
            </button>
        </div>
        <input
            :value="qty"
            class="form-control"
            :name="controlName"
            :v-validate="validations"
            data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;"
            readonly/>
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-light" @click="increaseQty()"><i class="fal fa-plus"></i>
            </button>
        </div>
        <span class="control-error" v-if="errors.has(controlName)">@{{ errors.first(controlName) }}</span>
    </div>

</script>

<script type="text/javascript">
    (() => {

        Vue.component('searchbar-component', {
            template: '#searchbar-template',
            data: function () {
                return {
                    compareCount: 0,
                    wishlistCount: 0,
                    searchedQuery: [],
                    isCustomer: '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                    address: null,
                }
            },

            watch: {
                '$root.headerCompareCount': function () {
                    this.updateCompareCount();
                },
                '$root.headerWishlistCount': function () {
                    this.updateWishlistCount();
                },
            },

            created: function () {
                this.updateCompareCount();
                this.updateWishlistCount();
            },

            methods: {
                getLocation(event) {
                    event.preventDefault();

                    navigator.geolocation.getCurrentPosition(position => {
                        this.$http.get("https://maps.googleapis.com/maps/api/geocode/json", {
                            params: {
                                latlng: position.coords.latitude.toString() + "," + position.coords.longitude.toString(),
                                key: "AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs",
                            }
                        })
                            .then(res => {
                                this.address = res.data.results[0].formatted_address
                            })
                    })

                },
                async updateWishlistCount() {
                    let wishlistItems = this.getStorageValue('wishlist_product');
                    if(!wishlistItems){
                        wishlistItems={};
                    }
                    if (!this.isCustomer) {
                        let count = 0;
                        if (typeof wishlistItems === 'object') {
                            for (let seller in wishlistItems) {
                                count += Object.keys(wishlistItems[seller]).length;
                            }
                        }
                        this.wishlistCount = count;
                    } else {
                        if (wishlistItems && Object.keys(wishlistItems).length > 0) {
                            await this.mergeGuestWishlistToCustomer(wishlistItems);
                        }
                        this.$http.get('{{route('marketplace.wishlist.count')}}')
                            .then(response => {
                                this.wishlistCount = response.data.count;
                            })
                            .catch(exception => {
                                console.log(this.__('error.something_went_wrong'));
                            });
                    }
                },
                async updateCompareCount() {
                    let comparedItems = this.getStorageValue('compared_product');
                    if(!comparedItems){
                        comparedItems=[];
                    }
                    if (!this.isCustomer) {
                        let count = 0;
                        if (typeof comparedItems === 'object') {
                            for (let seller in comparedItems) {
                                count += Object.keys(comparedItems[seller]).length;
                            }
                        }
                        this.compareCount = count;
                    } else {
                        if (comparedItems && Object.keys(comparedItems).length > 0) {
                            await this.mergeGuestCompareToCustomer(comparedItems);
                        }
                        this.$http.get('{{route('marketplace.compare.count')}}')
                            .then(response => {
                                this.compareCount = response.data.count;
                            })
                            .catch(exception => {
                                console.log(this.__('error.something_went_wrong'));
                            });
                    }
                },
                async mergeGuestCompareToCustomer(comparedItems) {
                    for (let seller in comparedItems) {
                        for (let productId in comparedItems[seller]) {
                            await this.$http.post(
                                `${this.$root.baseUrl}/marketplace/compare/add`, {
                                    productId: productId,
                                    marketplaceSellerId: seller,
                                }
                            );
                        }
                    }
                    this.setStorageValue('compared_product', {})
                    this.$root.headerCompareCount++;
                },
                async mergeGuestWishlistToCustomer(wishlistItems) {
                    for (let seller in wishlistItems) {
                        for (let productId in wishlistItems[seller]) {
                            await this.$http.post(
                                '{{route('marketplace.wishlist.add')}}', {
                                    productId: productId,
                                    marketplaceSellerId: seller,
                                }
                            );
                        }
                    }
                    this.setStorageValue('wishlist_product', {})
                    this.$root.headerCompareCount++;
                },
            },
        });

        Vue.component('quantity-changer', {
            template: '#quantity-changer-template',
            inject: ['$validator'],
            props: {
                controlName: {
                    type: String,
                    default: 'quantity'
                },

                quantity: {
                    type: [Number, String],
                    default: 1
                },

                minQuantity: {
                    type: [Number, String],
                    default: 1
                },

                validations: {
                    type: String,
                    default: 'required|numeric|min_value:1'
                },
                slotindex: null
            },

            data: function () {
                return {
                    qty: this.quantity
                }
            },

            watch: {
                quantity: function (val) {
                    this.qty = val;

                    this.$root.productCartQuantity = this.qty
                }
            },

            methods: {
                decreaseQty: function () {
                    if (this.qty > this.minQuantity)
                        this.qty = parseInt(this.qty) - 1;
                    if(this.slotindex != null){
                        this.$root.$emit("onUpdateTicketQuantity",{'quantity':this.qty,'index':this.slotindex} )
                    }
                    this.$root.productCartQuantity = this.qty
                },

                increaseQty: function () {
                    this.qty = parseInt(this.qty) + 1;
                    if(this.slotindex != null){
                        this.$root.$emit("onUpdateTicketQuantity", {'quantity':this.qty,'index':this.slotindex} )

                    }
                    this.$root.productCartQuantity = this.qty
                }
            }
        });

    })()
</script>
