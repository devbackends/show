@php
    $count = $velocityMetaData ? $velocityMetaData->featured_product_count : 12;
@endphp

<featured-products></featured-products>

@push('scripts')
    <script type="text/x-template" id="featured-products-template">
        <div class="product-list product-list-featured">
            <div class="container">
                <div class="row">
                    <div class="col product-list__head">
                        <h2 class="h1">{{ __('shop::app.home.whats-hot') }}</h2>
                        {{--<div class="action">
                            <select name="select" class="form-control">
                                <option value="0">All categories</option>
                                <option value="1">Category 1</option>
                                <option value="2">Category 2</option>
                            </select>
                            <a href="buy.html" class="btn btn-primary">See all</a>
                        </div>--}}
                    </div>
                </div>

                <shimmer-component v-if="isLoading" shimmer-count="6" class="d-none d-lg-block"></shimmer-component>

                <shimmer-component v-if="isLoading" shimmer-count="2" class="d-none d-sm-block d-lg-none"></shimmer-component>

                <shimmer-component v-if="isLoading" shimmer-count="1" class="d-block d-sm-none"></shimmer-component>

                <div v-if="isProductListLoaded" class="row mx-n1">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 px-1 my-1 product-item__card"
                         :key="index"
                         v-for="(product, index) in featuredProducts.slice(0, 6)">

                        <product-card
                            :list="list"
                            :product="product">

                        </product-card>

                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">

        (() => {
            Vue.component('featured-products', {
                'template': '#featured-products-template',
                data: function () {
                    return {
                        'list': false,
                        'isLoading': true,
                        'isProductListLoaded': false,
                        'featuredProducts': [],
                    }
                },

                mounted: function () {
                    this.getFeaturedProducts();
                },

                methods: {
                    'getFeaturedProducts': function () {
                        this.$http.get(`${this.baseUrl}/marketplace/category-details?category-slug=featured-products&count={{ $count }}`)
                            .then(response => {
                                if (response.data.status) {
                                    $(".product-list-featured").removeClass('hide');
                                    this.isProductListLoaded = true;
                                    this.featuredProducts = response.data.products;
                                    if(response.data.products.length>0){
                                        $(".product-list-featured").removeClass('hide');
                                    }else{
                                        $(".product-list-featured").addClass('hide');
                                    }
                                }else{
                                    $(".product-list-featured").addClass('hide');
                                }

                                this.isLoading = false;
                            })
                            .catch(error => {
                                $(".product-list-featured").addClass('hide');
                                this.isLoading = false;
                                console.log(this.__('error.something_went_wrong'));
                            })
                    }
                }
            })
        })()
    </script>
@endpush
