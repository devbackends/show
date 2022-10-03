@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

@extends('shop::layouts.master')

@section('page_title')
    {{ $category->meta_title ?? $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $category->meta_description }}" />
    <meta name="keywords" content="{{ $category->meta_keywords }}" />

    <meta property="og:title" content="Category Page" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.2agunshow.com/category/{{ strtolower($category->name)}}" />
    <meta property="og:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="og:description" content="{{ $category->meta_description }}" />

    <meta property="twitter:title" content="Category Page" />
    <meta property="twitter:type" content="website" />
    <meta property="twitter:url" content="https://www.2agunshow.com/{{ strtolower($category->name)}}" />
    <meta property="twitter:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="twitter:description" content="{{ $category->meta_description }}" />


@stop

@push('css')
    <style type="text/css">
        .product-price span:first-child, .product-price span:last-child {
            font-size: 18px;
            font-weight: 600;
        }

        @media only screen and (max-width: 992px) {
            .main-content-wrapper .vc-header {
                box-shadow: unset;
            }
        }
    </style>
@endpush

@php
    $isDisplayMode = in_array(
        $category->display_mode, [
            null,
            'products_only',
            'products_and_description'
        ]
    );
@endphp

@section('content-wrapper')
    <category-component></category-component>
@stop

@push('scripts')
    <script type="text/x-template" id="category-template">
        <div class="">
            <div class="container my-0 my-sm-5">
                <div class="row">

                    {!! view_render_event('bagisto.shop.productOrCategory.index.before', ['category' => $category]) !!}

                    <div class="col-sm-3 d-none d-sm-block" v-if="!isMobileDevice">
                        @if ($isDisplayMode)
                            @include('shop::products.list.layered-navigation')
                        @endif
                    </div>

                    <div class="col-sm-9 my-4 my-sm-0">
                        <div class="product-list__head mb-4">
                            <div class="row mb-3">
                                <div class="col">
                                    <h2 class="mb-0">{{ $category->name }}</h2>
                                </div>
                                <div class="col-auto pl-0 d-sm-none">
                                    <button class="btn btn-link text-decoration-none p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                                        <i class="far fa-filter mr-2"></i>Filter
                                    </button>
                                </div>
                            </div>

                            @if ($isDisplayMode && $category->description)
                                <div class="product-list__description mb-4">
                                    {!! $category->description !!}
                                </div>
                            @endif

                            <div class="w-100 collapse d-sm-none" id="collapseFilter" v-if="isMobileDevice">
                                @if ($isDisplayMode)
                                    @include('shop::products.list.layered-navigation')
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

                                <template v-else-if="products.length > 0 && !isLoading">
                                    @if ($toolbarHelper->getCurrentMode() == 'grid')
                                        <div class="row mx-n1">
                                            <div class="product-item__card col-12 col-sm-6 col-lg-3 px-1"
                                                 :key="index"
                                                 v-for="(product, index) in products">
                                                <product-card
                                                        :product="product">
                                                </product-card>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row mx-n1">
                                            <div class="col-12 px-1" v-for="(product, index) in products">
                                                <product-card
                                                        list=true
                                                        :key="index"
                                                        :product="product">
                                                </product-card>
                                            </div>
                                        </div>
                                    @endif

                                    {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.before', ['category' => $category]) !!}

                                    <div class="bottom-toolbar" v-html="paginationHTML"></div>

                                    {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.after', ['category' => $category]) !!}
                                </template>

                                <div class="empty" v-else-if="products.length == 0 && !isLoading">
                                    <p class="product-list__items-emtpy-title">{{ __('shop::app.products.whoops') }}</p>
                                    <p>{{ __('shop::app.products.empty') }}</p>
                                </div>
                                <!-- <a href="/marketplace-start-selling" class="d-block d-sm-none mt-4">
                                    <img src="/themes/market/assets/images/2a_2020_nov_promo_5.jpg" alt="" class="w-100">
                                </a> -->
                            @endif
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.productOrCategory.index.after', ['category' => $category]) !!}

                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('category-component', {
            template: '#category-template',

            data: () => ({
                products: [],
                isLoading: true,
                paginationHTML: ''
            }),

            mounted() {
                const pageFilterData=localStorage.getItem('pageFilterData');
                let x='';
                if(pageFilterData){
                    const data=JSON.parse(pageFilterData);
                    if(typeof data.category !='undefined'){
                        if(data.category=={{$category->id}}){
                            const currentDate=new Date();
                            const lastFilterDate=new Date(data.time);
                            var Difference_In_Time = currentDate- lastFilterDate;
                            var diffMins = Math.round(((Difference_In_Time % 86400000) % 3600000) / 60000); // minutes
                            if(diffMins < 4){
                                delete data.time;
                                delete data.category;
                                const qs = Object.keys(data)
                                    .map(key => `${key}=${data[key]}`)
                                    .join('&');
                                 x='?'+qs;
                            }
                        }
                    }
                }

                this.getCategoryProducts(x);

                eventBus.$on('reload-products', search => {
                    let s2 = search;
                    if (search[1] == '?') {
                        s2 = search.substring(1);
                    }
                    const x = s2.split("&");
                    for (let i = 0; i < x.length; i++) {
                        const s = x[i].split('=');
                        if (s[0] == 'page') {
                            const pageFilterData = localStorage.getItem('pageFilterData');
                            if (pageFilterData) {
                                const data = JSON.parse(pageFilterData);
                                data.page = [parseInt(s[1])];
                                data.time=new Date();
                                localStorage.setItem('pageFilterData', JSON.stringify(data));
                            }else{
                                const data={};
                                data.page=[parseInt(s[1])];
                                data.time=new Date();
                                data.category={{$category->id}};
                                localStorage.setItem('pageFilterData', JSON.stringify(data));
                            }
                        }
                    }
                    this.isLoading = true;
                    this.getCategoryProducts(search);
                });
            },

            methods: {
                getCategoryProducts(search = false) {
                    this.products=[];
                    this.$http.get(`${this.$root.baseUrl}/category-products/{{ $category->id }}${search ? search : window.location.search}`).then(response => {
                        this.isLoading = false;
                        if(typeof response.data.products.data != 'undefined'){
                            this.products = response.data.products.data;
                            this.paginationHTML = response.data.paginationHTML;
                        }
                    }).catch(error => {
                        this.isLoading = false;
                        console.log(this.__('error.something_went_wrong'));
                    })
                },
            },
            computed:{
                isMobileDevice(){
                    if($(window).width() >= 576){
                        return false;
                    }
                    return true;
                }
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
