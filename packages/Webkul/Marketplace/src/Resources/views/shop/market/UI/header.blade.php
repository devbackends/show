<script type="text/x-template" id="content-header-template">
    <div>
        <!-- Site wide announcement -->
    <!-- @if (!str_contains(url()->current(), '/customer') && !str_contains(url()->current(), '/marketplace/account'))
        <div class="d-block d-md-none">
            @include('shop::layouts.header.site-wide-announcement')
            </div>
@endif -->
        <!-- END Site wide announcement -->
        <div id="category-menu-header" class="row vc-header header-shadow active">
            <!-- Menu mobile version -->
            <div class="vc-small-screen container d-block d-lg-none">
                <div class="row">
                    <div class="col-6">
                        <div v-if="hamburger" class="nav-container scrollable bg-dark">
                            <div class="wrapper" v-if="rootCategories">
                                <div class="greeting drawer-section d-flex">
                                    <i class="fal fa-user mr-3"></i>
                                    <span>
                                    @guest('customer')
                                            <a class="unset font-weight-bold" href="{{ route('customer.session.index') }}">
                                        {{ __('velocity::app.responsive.header.greeting', ['customer' => 'Guest']) }}
                                        </a>
                                        @endguest

                                        @auth('customer')
                                            <a class="unset font-weight-bold" href="{{ route('customer.profile.index') }}">
                                            {{ __('velocity::app.responsive.header.greeting', ['customer' => auth()->guard('customer')->user()->first_name]) }}
                                        </a>
                                        @endauth
                                    </span>
                                    <i class="far fa-times ml-auto" @click="closeDrawer()"></i>
                                </div>

                                @php
                                    $currency = $locale = null;

                                    $currentLocale = app()->getLocale();
                                    $currentCurrency = core()->getCurrentCurrencyCode();

                                    $allLocales = core()->getCurrentChannel()->locales;
                                    $allCurrency = core()->getCurrentChannel()->currencies;
                                @endphp

                                @foreach ($allLocales as $appLocale)
                                    @if ($appLocale->code == $currentLocale)
                                        @php
                                            $locale = $appLocale;
                                        @endphp
                                    @endif
                                @endforeach

                                @foreach ($allCurrency as $appCurrency)
                                    @if ($appCurrency->code == $currentCurrency)
                                        @php
                                            $currency = $appCurrency;
                                        @endphp
                                    @endif
                                @endforeach

                                <ul type="none" class="velocity-content">
                                    @auth('customer')
                                    <li>
                                        <a href="{{ route('marketplace.account.products.create') }}" class="bg-primary text-black"><i class="far fa-plus mr-2"></i>Add Product</a>
                                    </li>
                                    @endauth
                                    <li :key="index" v-for="(content, index) in headerContent">
                                        <a
                                            class="unset"
                                            v-text="content.title"
                                            :href="`${$root.baseUrl}/${content.page_link}`">
                                        </a>
                                    </li>
                                </ul>

                                <ul type="none" class="category-wrapper">
                                    <li @click="toggleCategories($event)" class="d-flex w-100 align-items-stretch mobile-nav__category-item">
                                        <a class="unset">
                                            <i class="far fa-search mr-2"></i>
                                            <span>Categories</span>
                                        </a>
                                        <div class="d-flex align-items-center px-3 mobile-nav__category-arrow">
                                            <i class="far fa-chevron-right"></i>
                                        </div>
                                    </li>
                                </ul>

                                @auth('customer')
                                    <ul type="none" class="vc-customer-options">

                                        <li @click="toggleUserAccountSettings($event)" class="d-flex w-100 align-items-stretch">
                                            <a class="unset">
                                                <i class="far fa-user-edit mr-2"></i>
                                                <span>{{ __('shop::app.header.profile') }}</span>
                                            </a>
                                            <div class="d-flex align-items-center px-3 mobile-nav__category-arrow">
                                                <i class="far fa-chevron-right"></i>
                                            </div>
                                        </li>

                                        <li @click="toggleUserSellerTools($event)" class="d-flex w-100 align-items-stretch">
                                            <a class="unset">
                                                <i class="far fa-store mr-2"></i>
                                                <span>{{ __('marketplace::app.shop.layouts.marketplace') }}</span>
                                            </a>
                                            <div class="d-flex align-items-center px-3 mobile-nav__category-arrow">
                                                <i class="far fa-chevron-right"></i>
                                            </div>
                                        </li>

                                    </ul>
                                @endauth

                                <ul type="none" class="meta-wrapper">
                                    <li>
                                        @auth('customer')
                                            <a
                                                class="unset btn btn-primary"
                                                href="{{ route('customer.session.destroy') }}">
                                                <span>{{ __('shop::app.header.logout') }}</span>
                                            </a>
                                        @endauth

                                        @guest('customer')
                                            <a
                                                class="unset btn btn-primary"
                                                href="{{ route('customer.session.create') }}">
                                                <span>{{ __('shop::app.customer.login-form.title') }}</span>
                                            </a>
                                        @endguest
                                    </li>
                                </ul>
                            </div>

                            <div class="wrapper" v-else-if="categories">
                                <div class="drawer-section">
                                    <i class="far fa-chevron-left mr-3" @click="toggleSubcategories('root')"></i>

                                    <p class="display-inbl mb-0 font-weight-bold">Categories</p>

                                    <i class="far fa-times ml-auto" @click="closeDrawer()"></i>
                                </div>

                                <ul type="none">
                                    <li
                                        :key="index"
                                        v-for="(category, index) in categories"
                                        class="d-flex w-100 align-items-stretch mobile-nav__category-item">
                                        <a
                                            class="unset"
                                            :href="`${$root.baseUrl}/category/${category.slug}`">

                                            <div class="category-logo">
                                                <img
                                                    class="category-icon"
                                                    v-if="category.category_icon_path"
                                                    :src="`${$root.baseUrl}/storage/${category.category_icon_path}`" />
                                            </div>
                                            <span v-text="category.name"></span>
                                        </a>
                                        <div v-if="category.children.length > 0" @click="toggleSubcategories(index, $event)" class="d-flex align-items-center px-3 mobile-nav__category-arrow">
                                            <i class="far fa-chevron-right"></i>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="wrapper" v-else-if="subCategory">
                                <div class="drawer-section">
                                    <i class="far fa-chevron-left mr-3" @click="toggleSubcategories('root')"></i>

                                    <h4 class="display-inbl mb-0" v-text="subCategory.name"></h4>

                                    <i class="far fa-times ml-auto" @click="closeDrawer()"></i>
                                </div>

                                <ul type="none">
                                    <li
                                        :key="index"
                                        v-for="(nestedSubCategory, index) in subCategory.children">

                                        <a
                                            class="unset"
                                            :href="`${$root.baseUrl}/category/${subCategory.slug}/${nestedSubCategory.slug}`">

                                            <div class="category-logo">
                                                <img
                                                    class="category-icon"
                                                    v-if="nestedSubCategory.category_icon_path"
                                                    :src="`${$root.baseUrl}/storage/${nestedSubCategory.category_icon_path}`" />
                                            </div>
                                            <span v-text="nestedSubCategory.name"></span>
                                        </a>

                                        <ul
                                            type="none"
                                            class="nested-category"
                                            v-if="nestedSubCategory.children && nestedSubCategory.children.length > 0">

                                            <li
                                                :key="`index-${Math.random()}`"
                                                v-for="(thirdLevelCategory, index) in nestedSubCategory.children">
                                                <a
                                                    class="unset"
                                                    :href="`${$root.baseUrl}/category/${subCategory.slug}/${nestedSubCategory.slug}/${thirdLevelCategory.slug}`">

                                                    <div class="category-logo">
                                                        <img
                                                            class="category-icon"
                                                            v-if="thirdLevelCategory.category_icon_path"
                                                            :src="`${$root.baseUrl}/storage/${thirdLevelCategory.category_icon_path}`" />
                                                    </div>
                                                    <span v-text="thirdLevelCategory.name"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <div class="wrapper" v-else-if="languages">
                                <div class="drawer-section">
                                    <i class="far fa-chevron-left" @click="toggleSubcategories('root')"></i>
                                    <h4 class="display-inbl">Languages</h4>
                                    <i class="material-icons pull-right" @click="closeDrawer()">cancel</i>
                                </div>

                                <ul type="none">
                                    @foreach ($allLocales as $locale)
                                        <li>
                                            <a
                                                class="unset"
                                                @if (isset($serachQuery))
                                                href="?{{ $serachQuery }}&locale={{ $locale->code }}"
                                                @else
                                                href="?locale={{ $locale->code }}"
                                                @endif>

                                                <div class="category-logo">
                                                    <img
                                                        class="category-icon"
                                                        src="{{ asset('/storage/' . $locale->locale_image) }}" />
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="wrapper" v-else-if="currencies">
                                <div class="drawer-section">
                                    <i class="far fa-chevron-left" @click="toggleSubcategories('root')"></i>
                                    <h4 class="display-inbl">Currencies</h4>
                                    <i class="material-icons pull-right" @click="closeDrawer()">cancel</i>
                                </div>

                                <ul type="none">
                                    @foreach ($allCurrency as $currency)
                                        <li>
                                            @if (isset($serachQuery))
                                                <a
                                                    class="unset"
                                                    href="?{{ $serachQuery }}&locale={{ $currency->code }}">
                                                    <span>{{ $currency->code }}</span>
                                                </a>
                                            @else
                                                <a
                                                    class="unset"
                                                    href="?locale={{ $currency->code }}">
                                                    <span>{{ $currency->code }}</span>
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="wrapper" v-else-if="userAccountSettings">
                                <div class="drawer-section">
                                    <i class="far fa-chevron-left mr-3" @click="toggleSubcategories('root')"></i>

                                    <p class="display-inbl mb-0 font-weight-bold">My Account</p>

                                    <i class="far fa-times ml-auto" @click="closeDrawer()"></i>
                                </div>

                                <ul type="none">
                                    <li>
                                        <a href="{{ route('customer.profile.index') }}" class="unset">
                                            <i class="icon orders text-down-3"></i>
                                            <span>{{ __('shop::app.header.profile') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('customer.orders.index') }}" class="unset">
                                            <i class="icon orders text-down-3"></i>
                                            <span>{{ __('shop::app.header.order') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('customer.wishlist.index') }}" class="unset">
                                            <i class="icon wishlist text-down-3"></i>
                                            <span>{{ __('shop::app.header.wishlist') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="/customer/account/comparison" class="unset">
                                            <i class="icon wishlist text-down-3"></i>
                                            <span>{{ __('shop::app.header.compare') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('customer.reviews.index') }}" class="unset">
                                            <i class="icon reviews text-down-3"></i>
                                            <span>{{ __('velocity::app.shop.general.reviews') }}</span>
                                        </a>
                                    </li>


                                    <li>
                                        <a href="{{ route('customer.address.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('velocity::app.shop.general.addresses') }}</span>
                                        </a>
                                    </li>


                                    <li>
                                        <a href="/customer/account/rma" class="unset">
                                            <span>{{ __('shop::app.header.returns') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>

                            <div class="wrapper" v-else-if="userSellerTools">
                                <div class="drawer-section">
                                    <i class="far fa-chevron-left mr-3" @click="toggleSubcategories('root')"></i>

                                    <p class="display-inbl mb-0 font-weight-bold">Selling</p>

                                    <i class="far fa-times ml-auto" @click="closeDrawer()"></i>
                                </div>

                                <ul type="none">
                                    <li>
                                        <a href="{{ route('marketplace.account.seller.edit') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.profile') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('marketplace.account.dashboard.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.dashboard') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketplace.account.products.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.products') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('marketplace.account.orders.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.orders') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketplace.account.transactions.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.transactions') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketplace.account.reviews.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.reviews') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketplace.account.settings.index') }}" class="unset">
                                            <i class="icon address text-down-3"></i>
                                            <span>{{ __('marketplace::app.shop.layouts.store-settings') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>


                        </div>

                        <div class="hamburger-wrapper" @click="toggleHamburger">
                            <i class="far fa-bars"></i>
                        </div>

                        <!-- <logo-component></logo-component> -->
                        <a href="/" class="brand-logo"></a>
                    </div>

                    <div class="right-vc-header col-6">
                        <a
                            class="compare-btn unset"
                            @auth('customer')
                            href="{{ route('velocity.customer.product.compare') }}"
                            @endauth

                            @guest('customer')
                            href="{{ route('velocity.product.compare') }}"
                            @endguest
                        >
                            <i class="far fa-compress-alt"></i>
                        </a>

                        <a class="wishlist-btn unset" :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                            <i class="far fa-heart"></i>
                        </a>

                        <a class="unset cursor-pointer" @click="openSearchBar">
                            <i class="far fa-search"></i>
                        </a>
                        <mobile-mini-cart checkout-url="{{route('marketplace.cart.view')}}"></mobile-mini-cart>
                    </div>

                    <searchbar-component v-if="isSearchbar"></searchbar-component>
                </div>
            </div>
            <!-- END Menu mobile version -->


            <a id="shop-by-category-button" class="main-category left d-none d-lg-block"
               @mouseout="toggleSidebar('0', $event, 'mouseout')"
               @mouseover="toggleSidebar('0', $event, 'mouseover')"
               :class="`main-category unselectable ${($root.sharedRootCategories.length > 0) ? 'cursor-pointer' : 'cursor-not-allowed'} left`">
                <i class="far fa-list-ul"></i>
                <span class="pl5">{{ __('velocity::app.menu-navbar.text-category') }}</span>
            </a>




            <div class="content-list right">
                <ul class="list-unstyled">
                    <li v-for="(content, index) in headerContent">
                        <a v-text="content.title" :href="`${$root.baseUrl}/${content['page_link']}`"
                           v-if="(content['content_type'] == 'link' || content['content_type'] == 'category')"
                           :target="content['link_target'] ? '_blank' : '_self'">
                        </a>
                    </li>
                </ul>
            </div>
            @if(auth()->guard('customer')->user())
                @if (app('Webkul\Marketplace\Repositories\SellerRepository')->isSeller(auth()->guard('customer')->user()->id))
                    <a href="/marketplace/account/catalog/products/create" class="link ml-auto d-none d-lg-inline-block"><i class="far fa-plus-circle mr-2"></i>Add Product</a>
                @else
                <a href="/marketplace/start-selling" class="link ml-auto d-none d-lg-inline-block">Start Selling Now!</a>
                @endif
            @else
                <a href="/marketplace/start-selling" class="link ml-auto d-none d-lg-inline-block">Start Selling Now!</a>
            @endif
        </div>
    </div>
</script>

<script type="application/javascript">
    (() => {
        Vue.component('content-header', {
            template: '#content-header-template',
            props: [
                'headerContent',
            ],

            data: function () {
                return {
                    'languages': false,
                    'hamburger': false,
                    'currencies': false,
                    'userAccountSettings': false,
                    'userSellerTools': false,
                    'subCategory': null,
                    'isSearchbar': false,
                    'rootCategories': true,
                    categories: false,
                    'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            methods: {
                openSearchBar: function () {
                    this.isSearchbar = !this.isSearchbar;

                    let footer = $('.footer');
                    let homeContent = $('#home-right-bar-container');

                    if (this.isSearchbar) {
                        footer[0].style.opacity = '.3';
                        homeContent[0].style.opacity = '.3';
                    } else {
                        footer[0].style.opacity = '1';
                        homeContent[0].style.opacity = '1';
                    }
                },

                toggleHamburger: function () {
                    this.hamburger = !this.hamburger;
                },

                closeDrawer: function() {
                    $('.nav-container').hide();

                    this.toggleHamburger();
                    this.rootCategories = true;
                },

                toggleCategories: function (event) {
                    event.preventDefault();
                    this.categories = [...this.$root.sharedRootCategories];
                    this.rootCategories = false;
                    this.subCategory = false;
                },

                toggleSubcategories: function (index, event) {
                    if (index == "root") {
                        this.rootCategories = true;
                        this.categories = false;
                    } else {
                        event.preventDefault();

                        let categories = this.$root.sharedRootCategories;
                        this.rootCategories = false;
                        this.categories = false;
                        this.subCategory = categories[index];
                    }
                },

                toggleMetaInfo: function (metaKey) {
                    this.rootCategories = false;
                    this[metaKey] = !this[metaKey];
                },

                toggleUserAccountSettings: function (event) {
                    event.preventDefault();
                    this.rootCategories = false;
                    this.userSellerTools = false;
                    this.userAccountSettings = true;
                },

                toggleUserSellerTools: function (event) {
                    event.preventDefault();
                    this.rootCategories = false;
                    this.userAccountSettings = false;
                    this.userSellerTools = true;
                },

            },

            watch: {
                hamburger: function (value) {
                    if (value) {
                        document.body.classList.add('open-hamburger');
                    } else {
                        document.body.classList.remove('open-hamburger');
                    }
                },
            },

        });
    })()
</script>



