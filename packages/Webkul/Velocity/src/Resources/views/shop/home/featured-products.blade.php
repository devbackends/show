@php
    $count = $velocityMetaData ? $velocityMetaData->featured_product_count : 10;
@endphp

<featured-products></featured-products>

@push('scripts')
    <script type="text/x-template" id="featured-products-template">
        <div class="container featured-products py-5">
            <shimmer-component v-if="isLoading && !isMobileView" shimmer-count="5"></shimmer-component>

            <template v-else-if="featuredProducts.length > 0">
                <card-list-header heading="{{ __('shop::app.home.featured-products') }}">
                </card-list-header>

                <div class="carousel-products vc-full-screen ltr" v-if="!isMobileView">
                    <carousel-component
                        :slides-per-page="desktop_slides"
                        navigation-enabled="true"
                        pagination-enabled="hide"
                        id="fearured-products-carousel"
                        :slides-count="featuredProducts.length">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in featuredProducts">
                            <product-card
                                :list="list"
                                :product="product">
                            </product-card>
                        </slide>
                    </carousel-component>
                </div>

                <div class="carousel-products vc-small-screen" v-else>
                    <carousel-component
                        :slides-per-page="mobile_slides"
                        navigation-enabled="hide"
                        pagination-enabled="hide"
                        id="fearured-products-carousel"
                        :slides-count="featuredProducts.length">

                        <slide
                            :key="index"
                            :slot="`slide-${index}`"
                            v-for="(product, index) in featuredProducts">
                            <product-card
                                :list="list"
                                :product="product">
                            </product-card>
                        </slide>
                    </carousel-component>
                </div>
            </template>

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
                        'featuredProducts': [],
                        'isMobileView': this.$root.isMobile(),
                        'desktop_slides':  4,
                        'mobile_slides':  2,
                    }
                },

                mounted: function () {
                    this.getFeaturedProducts();
                },

                methods: {
                    'getFeaturedProducts': function () {
                        this.$http.get(`${this.baseUrl}/category-details?category-slug=featured-products&count={{ $count }}`)
                        .then(response => {
                            if (response.data.status)
                                this.featuredProducts = response.data.products;

                            this.initSlides();
                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                    },
                    'initSlides': function (){
                        this.desktop_slides = window.desktop_slides? window.desktop_slides: 4;
                        this.mobile_slides = window.mobile_slides? window.mobile_slides: 2;
                    }
                }
            })
        })()
    </script>
@endpush
