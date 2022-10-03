<script type="text/x-template" id="cart-btn-template">
    <button
            type="button"
            id="mini-cart"
            @click="toggleMiniCart"
            :class="`btn btn-link ${itemCount == 0 ? 'cursor-not-allowed' : ''}`">

        <div class="mini-cart-content">
            <span class=" icon cart-custom-icon">
                <span class="badge" v-text="itemCount" v-if="itemCount != 0"></span>
            </span>
            <span class="cart-btn-text">{{ __('velocity::app.minicart.cart') }}</span>
        </div>

    </button>
</script>

<script type="text/x-template" id="close-btn-template">
    <button type="button" class="close disable-box-shadow">
        <span class="white-text fs20" @click="togglePopup">×</span>
    </button>
</script>

<script type="text/x-template" id="quantity-changer-template">

    <div :class="`quantity control-group ${errors.has(controlName) ? 'has-error' : ''}`">
        <label class="required">{{ __('shop::app.products.quantity') }}</label>
        <button type="button" class="increase" @click="increaseQty()"><i class="fal fa-plus custom-increase-icon"></i></button>

        <input
                :value="qty"
                class="control"
                :name="controlName"
                :v-validate="validations"
                data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;"
                readonly />
        <button type="button" class="decrease" @click="decreaseQty()"><i class="fal fa-minus custom-decrease-icon"></i></button>


        <span class="control-error" v-if="errors.has(controlName)">@{{ errors.first(controlName) }}</span>
    </div>
</script>

@include('velocity::UI.header')

<script type="text/x-template" id="logo-template">
    <a
            :class="`left ${addClass}`"
            href="{{ route('shop.home.index') }}">

        @if ($logo = core()->getCurrentChannel()->logo_url)
            <img class="logo" src="{{ $logo }}" />
        @else
            <img src="{{asset('images/gun-black-logo.svg')}}" alt="2AGunShow" class="brand-logo-gunshow">
        @endif
    </a>
</script>

<script type="text/x-template" id="searchbar-template">
    <div class="row">
        <div class="col-lg" >
            <form
                    method="GET"
                    role="search"
                    id="search-form"
                    action="{{ route('velocity.search.index') }}">

                <div role="toolbar" class="row d-flex justify-content-center">
                    <div class="col-lg-3 pr-lg-0">
                        <select class="form-control" name="category" @change="focusInput($event)">
                            <option value="">
                                {{ __('velocity::app.header.all-categories') }}
                            </option>

                            <template v-for="(category, index) in $root.sharedRootCategories">
                                <option
                                        :key="index"
                                        selected="selected"
                                        :value="category.id"
                                        v-if="(category.id == searchedQuery.category)">
                                    @{{ category.name }}
                                </option>

                                <option :key="index" :value="category.id" v-else>
                                    @{{ category.name }}
                                </option>
                            </template>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <div class="btn-group full-width">
                            <input  id="search_products"
                                    required
                                    name="term"
                                    type="search"
                                    class="form-control"
                                    :value="searchedQuery.term ? searchedQuery.term.split('+').join(' ') : ''"
                                    placeholder="{{ __('velocity::app.header.search-text') }}" />

                            <button class="btn" type="submit" id="header-search-icon">
                                <span class=" icon custom-search-icon"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-lg-auto header-middle__btns">
            {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}
            @include('shop::checkout.cart.mini-cart')
            {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}
            <a
                    class="btn btn-link compare-btn unset"
                    @auth('customer')
                    href="{{ route('velocity.customer.product.compare') }}"
                    @endauth

                    @guest('customer')
                    href="{{ route('velocity.product.compare') }}"
                    @endguest
            >

                <span class="icon compare-custom-icon">
                <span class="badge" v-text="compareCount" v-if="compareCount > 0"></span>
                </span>
                <span class="compare-btn__text">{{ __('velocity::app.customer.compare.text') }}</span>
            </a>
            {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}
            <a class="btn btn-link wishlist-btn unset" :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                <span class="icon wishlist-custom-icon">
                <span class="badge" v-text="wishlistCount" v-if="wishlistCount > 0"></span>
                </span>
                <span class="wishlist-btn__text">{{ __('shop::app.layouts.wishlist') }}</span>
            </a>
            {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}
        </div>
    </div>
</script>

<script type="text/x-template" id="sidebar-categories-template">
    <div class="wrapper" v-if="rootCategories">
        {{--Hello World--}}
    </div>

    <div class="wrapper" v-else-if="subCategory">
        {{--Hello World 2--}}
    </div>
</script>
<script>
    $(function() {
        $( "#search_products" ).autocomplete({
            source: 'autocomplete-product',
        });
    });
</script>
<script type="text/javascript">
    (() => {
        Vue.component('cart-btn', {
        template: '#cart-btn-template',

        props: ['itemCount'],

        methods: {
            toggleMiniCart: function () {
                let modal = $('#cart-modal-content')[0];
                if (modal)
                    modal.classList.toggle('hide');

                let accountModal = $('.account-modal')[0];
                if (accountModal)
                    accountModal.classList.add('hide');

                event.stopPropagation();
            }
        }
    });

    Vue.component('close-btn', {
        template: '#close-btn-template',

        methods: {
            togglePopup: function () {
                $('#cart-modal-content').hide();
            }
        }
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
            }
        },

        data: function() {
            return {
                qty: this.quantity
            }
        },

        watch: {
            quantity: function (val) {
                this.qty = val;

                this.$emit('onQtyUpdated', this.qty)
            }
        },

        methods: {
            decreaseQty: function() {
                if (this.qty > this.minQuantity)
                    this.qty = parseInt(this.qty) - 1;

                this.$emit('onQtyUpdated', this.qty)
            },

            increaseQty: function() {
                this.qty = parseInt(this.qty) + 1;

                this.$emit('onQtyUpdated', this.qty)
            }
        }
    });

    Vue.component('logo-component', {
        template: '#logo-template',
        props: ['addClass'],
    });

    Vue.component('searchbar-component', {
        template: '#searchbar-template',
        data: function () {
            return {
                compareCount: 0,
                wishlistCount: 0,
                searchedQuery: [],
                isCustomer: '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
            }
        },

        watch: {
            '$root.headerItemsCount': function () {
                this.updateHeaderItemsCount();
            }
        },

        created: function () {
            let searchedItem = window.location.search.replace("?", "");
            searchedItem = searchedItem.split('&');

            let updatedSearchedCollection = {};

            searchedItem.forEach(item => {
                let splitedItem = item.split('=');
            updatedSearchedCollection[splitedItem[0]] = splitedItem[1];
        });

            this.searchedQuery = updatedSearchedCollection;

            this.updateHeaderItemsCount();
        },

        methods: {
            'focusInput': function (event) {
                $(event.target.parentElement.parentElement).find('input').focus();
            },

            'updateHeaderItemsCount': function () {
                if (! this.isCustomer) {
                    let comparedItems = this.getStorageValue('compared_product');
                    let wishlistedItems = this.getStorageValue('wishlist_product');

                    if (wishlistedItems) {
                        this.wishlistCount = wishlistedItems.length;
                    }

                    if (comparedItems) {
                        this.compareCount = comparedItems.length;
                    }
                } else {
                    this.$http.get(`${this.$root.baseUrl}/items-count`)
                        .then(response => {
                        this.compareCount = response.data.compareProductsCount;
                    this.wishlistCount = response.data.wishlistedProductsCount;
                })
                .catch(exception => {
                        console.log(this.__('error.something_went_wrong'));
                });
                }
            }
        }
    });
    })()
</script>