@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.search.page-title') }}
@endsection
@php $isDisplayMode=true; @endphp
@section('content-wrapper')
    <search-component></search-component>
    <div class="bg-gray-extralight py-5">
        <div class="container">
            <p class="font-paragraph-big-bold mb-1">Not finding what you're looking for?</p>
            <h2 class="mb-3">Try searching by category</h2>
            <BuyAllNavCategories></BuyAllNavCategories>
        </div>
        <div class="my-n5">

            <div class="other-categories py-5">
                <div class="container">
                    <p class="font-paragraph-big-bold">Other Categories</p>
                    <div class="row mx-n2">
                        <div class="col-12 col-sm-6 col-md-4 col-lg px-2">
                            <a href="{{route('marketplace.instructors.index')}}" class="other-categories__item">
                                <img src="/themes/market/assets/images/cta-category-classes.jpg" alt="">
                                <p class="other-categories__item-title">Classes</p>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg px-2">
                            <a href="/category/parts" class="other-categories__item">
                                <img src="/themes/market/assets/images/cta-category-parts.jpg" alt="">
                                <p class="other-categories__item-title">Parts</p>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg px-2">
                            <a href="/category/accessory/optics-sights" class="other-categories__item">
                                <img src="/themes/market/assets/images/cta-category-optics.jpg" alt="">
                                <p class="other-categories__item-title">Optics & Sights</p>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg px-2">
                            <a href="/category/accessory/reloading-supplies" class="other-categories__item">
                                <img src="/themes/market/assets/images/cta-category-reloading.jpg" alt="">
                                <p class="other-categories__item-title">Reloading Supplies</p>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg px-2">
                            <a href="/category/survival-gear" class="other-categories__item">
                                <img src="/themes/market/assets/images/cta-category-gear.jpg" alt="">
                                <p class="other-categories__item-title">Survival Gear</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script type="text/x-template" id="search-component-template">

    <div class="container my-0 my-sm-5">
        <div class="row">

            <div class="col-sm-3 d-none d-sm-block">
                @if ($isDisplayMode)
                    @include('shop::products.list.layered-navigation', ['showSearch'=>false])
                @endif
            </div>
            <div class="col-sm-9 my-4 my-sm-0">
                <div class="product-list__head mb-4">
                    <div class="row mb-3">
                        <div class="col">
                            <h2 class="mb-0" v-if="!isLoading && data.length == 0" v-text="`No Results Found`"></h2>
                            <h2 class="mb-0" v-if="!isLoading && data.length == 1" v-text="`Search Result`"></h2>
                            <h2 class="mb-0" v-if="!isLoading && data.length > 1"  v-text="`Search Results`"></h2>
                        </div>
                        <div class="col-auto pl-0 d-sm-none">
                            <button class="btn btn-link text-decoration-none p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                                <i class="far fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </div>

                    <div class="w-100 collapse d-sm-none" id="collapseFilter">
                        @if ($isDisplayMode)
                            @include('shop::products.list.layered-navigation', ['showSearch'=>false])
                        @endif
                    </div>

                    @include ('shop::products.list.toolbar')
                </div>
                <div class="">
                    @if ($isDisplayMode)

                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                            <shimmer-component v-if="isLoading" shimmer-count="4" class="d-none d-lg-block"></shimmer-component>

                            <shimmer-component v-if="isLoading" shimmer-count="2" class="d-none d-sm-block d-lg-none"></shimmer-component>

                            <shimmer-component v-if="isLoading" shimmer-count="1" class="d-block d-sm-none"></shimmer-component>
                        @else
                            <shimmer-component v-if="isLoading" list=true shimmer-count="3" class="d-none d-lg-block"></shimmer-component>
                        @endif

                        <template v-else-if="data.length > 0 && !isLoading">
                            @if ($toolbarHelper->getCurrentMode() == 'grid')
                                <div class="row mx-n1">
                                    <div class="product-item__card col-12 col-sm-6 col-lg-3 px-1"
                                         :key="index"
                                         v-for="(item, index) in data">
                                        <product-card v-if="type === 'product'" :product="item"></product-card>
                                        <category-card v-else-if="type === 'category'" :category="item"></category-card>
                                        <div class="show-card show-card__ffl h-100 p-3" v-else-if="type === 'seller'">
                                            <div class="row align-items-center" id="">
                                                <div class="col-auto pr-0">
                                                    <img v-if="item.image_url" :src="item.image_url" alt="" height="60px" width="60px" class="rounded-circle mx-auto">
                                                    <img v-else src="{{ asset('themes/market/assets/images/seller-default-logo.png') }}" alt="" height="60px" width="60px" class="rounded-circle mx-auto">
                                                </div>
                                                <div class="col">
                                                    <a :href="item.shop_url" tabindex="0">
                                                        <p class="font-weight-bold mb-0">@{{item.shop_title}}</p>
                                                    </a>
                                                    <p>@{{item.city}}, @{{item.state}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row mx-n1">
                                    <div class="col-12 px-1" v-for="(item, index) in data">
                                        <product-card v-if="type === 'product'" :product="item" list="true"></product-card>
                                        <category-card v-else-if="type === 'category'" :category="item"></category-card>
                                        <div class="show-card show-card__ffl h-100 p-3" v-else-if="type === 'seller'">
                                            <div class="row align-items-center" id="">
                                                <div class="col-auto pr-0">
                                                    <img v-if="item.image_url" :src="item.image_url" alt="" height="60px" width="60px" class="rounded-circle mx-auto">
                                                    <img v-else src="{{ asset('themes/market/assets/images/seller-default-logo.png') }}" alt="" height="60px" width="60px" class="rounded-circle mx-auto">
                                                </div>
                                                <div class="col">
                                                    <a :href="item.shop_url" tabindex="0">
                                                        <p class="font-weight-bold mb-0">@{{item.shop_title}}</p>
                                                    </a>
                                                    <p>@{{item.city}}, @{{item.state}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.before') !!}

                            <div class="bottom-toolbar" v-html="paginationHTML"></div>

                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.after') !!}
                        </template>

                        <div class="empty" v-else-if="data.length == 0 && !isLoading">
                            <p class="product-list__items-emtpy-title">{{ __('shop::app.products.whoops') }}</p>
                            <p>{{ __('shop::app.products.empty') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</script>

<script>
    Vue.component('search-component', {
        template: '#search-component-template',

        data: function() {
            return {
                data: [],
                type: '{{$_GET['type'] ?? 'product'}}',
                search: '{{$_GET['search']}}',
                isLoading: true,
                paginationHTML: '',
            }
        },
        mounted() {
            this.getSearchData();
            eventBus.$on('reload-products', search => {
                this.isLoading = true;
                this.getSearchData(search);
            });
        },
        methods: {
            getSearchData(search = false) {
                const this_this = this;
                this.$http.get(`${this.$root.baseUrl}/search-result/${this.type}/${this.search}${search ? search : window.location.search}`).then(response => {
                    this_this.isLoading = false;
                  if(typeof response.data.data != 'undefined'){
                      this_this.data = response.data.data;
                      this_this.paginationHTML = response.data.paginationHTML;
                    }
                }).catch(error => {
                    this_this.isLoading = false;
                })
            },
        }
    })
</script>
<script>
    $(document).ready(() => {
        $(document).on('click', '.pagination .page-item', e => {
            e.preventDefault();
            var href = e.target.href;
            if(!href){
                href = $(e.target).parent()[0].href;
            }
            eventBus.$emit('reload-products', '?' + href.split('?')[1]);
        })
    })
</script>
@endpush