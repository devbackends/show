@php
$count = $velocityMetaData ? $velocityMetaData->new_products_count : 12;
@endphp

<new-products></new-products>

@push('scripts')
<script type="text/x-template" id="new-products-template">

    <div class="product-list recent-products-list py-5">

        <div class="container pb-5">
            <div class="row">
                <div class="col product-list__head">
                    <h2 class="h1">{{ __('shop::app.home.new-products') }}</h2>
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


            <!-- SLIDER CONTENT -->
            <div v-if="isProductListLoaded" class="product-list__new-products">
            <carousel-component
                slides-per-page="6"
                navigation-enabled="true"
                pagination-enabled="hide"
                id="new-products-carousel"
                :slides-count="newProducts.length"
                class="d-none d-lg-block">

                <slide
                        :key="index"
                        :slot="`slide-${index}`"
                        v-for="(product, index) in newProducts">
                        <product-card :list="list" :product="product"></product-card>
                </slide>
            </carousel-component>
            <carousel-component
                slides-per-page="2"
                navigation-enabled="true"
                pagination-enabled="hide"
                id="new-products-carousel"
                :slides-count="newProducts.length"
                class="d-none d-sm-block d-lg-none">

                <slide
                        :key="index"
                        :slot="`slide-${index}`"
                        v-for="(product, index) in newProducts">
                        <product-card :list="list" :product="product"></product-card>
                </slide>
            </carousel-component>
            <carousel-component
                slides-per-page="1"
                navigation-enabled="true"
                pagination-enabled="hide"
                id="new-products-carousel"
                :slides-count="newProducts.length"
                class="d-block d-sm-none">

                <slide
                        :key="index"
                        :slot="`slide-${index}`"
                        v-for="(product, index) in newProducts">
                        <product-card :list="list" :product="product"></product-card>
                </slide>
            </carousel-component>
                            </div>


            <!-- END SLIDER CONTENT -->
        </div>
    </div>
</script>

<script type="text/javascript">
    (() => {
        Vue.component('new-products', {
            'template': '#new-products-template',
            data: function() {
                return {
                    'list': false,
                    'isLoading': true,
                    'isProductListLoaded': false,
                    'newProducts': [],
                    'isMobileView': this.$root.isMobile(),
                }
            },

            mounted: function() {
                this.getNewProducts();
            },

            methods: {
                'getNewProducts': function() {
                    this.$http.get(`${this.baseUrl}/marketplace/category-details?category-slug=new-products&count={{ $count }}`)
                        .then(response => {
                            if (response.data.status){
                                $(".recent-products-list").removeClass('hide');
                                this.isProductListLoaded = true;
                                this.newProducts = response.data.products;
                                if(response.data.products.length>0){
                                    $(".recent-products-list").removeClass('hide');
                                }else{
                                    $(".recent-products-list").addClass('hide');
                                }
                            }else{
                                $(".recent-products-list").addClass('hide');
                            }


                            this.isLoading = false;
                        })
                        .catch(error => {
                            $(".recent-products-list").addClass('hide');
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                }
            }
        })
    })()
</script>

@endpush