@inject ('velocityHelper', 'Webkul\Velocity\Helpers\Helper')
@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

<recently-viewed
        add-class="{{ isset($addClass) ? $addClass : '' }}"
        quantity={{ isset($quantity) ? $quantity : null }}
                add-class-wrapper="{{ isset($addClassWrapper) ? $addClassWrapper : '' }}">
</recently-viewed>

@push('scripts')
    <script type="text/x-template" id="recently-viewed-template">
        <div :class="`${addClass} recently-viewed`">
            <h3 class="heading mb-4">{{ __('velocity::app.products.recently-viewed') }}</h3>

            <div :class="`row recetly-viewed-products-wrapper ${addClassWrapper}`">
                <div
                        :key="Math.random()"
                        class="col-12 col-md-6 col-lg-4"
                        v-for="(product, index) in recentlyViewed">
                    <div class="small-card-container">
                        <div class="container">
                        <div class="row">
                            <div class="col-3 px-0 product-image-container">
                                <a :href="`${baseUrl}/product/${product.urlKey}`" class="unset">
                                    <div class="product-image w-100" :style="`background-image: url(${product.image});width:95px;height:95px;`"></div>
                                </a>
                            </div>
                            <div class="col-9 pr-0 align-vertical-top" v-if="product.name">
                                <a :href="`${baseUrl}/product/${product.urlKey}`" class="unset">
                                    <div class="product-name">
                                        <p class="font-weight-bold mb-0">@{{ product.name }}</p>
                                    </div>
                                </a>
                                <p class="font-weight-bold mb-0"  v-html="product.productPrice"></p>
                                <star-ratings v-if="product.rating > 0"
                                            push-class="display-inbl"
                                            :ratings="product.rating">
                                </star-ratings>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <span
                        class="fs16"
                        v-if="!recentlyViewed ||(recentlyViewed && Object.keys(recentlyViewed).length == 0)"
                        v-text="'{{ __('velocity::app.products.not-available') }}'">
                </span>
            </div>
        </div>
    </script>
    <script>
        $(document).ready(function(){

        });
    </script>
    <script type="text/javascript">
        (() => {
            Vue.component('recently-viewed', {
            template: '#recently-viewed-template',
            props: ['quantity', 'addClass', 'addClassWrapper'],

            data: function () {
                return {
                    recentlyViewed: (() => {
                        let storedRecentlyViewed = window.localStorage.recentlyViewed;
                if (storedRecentlyViewed) {
                    var slugs = JSON.parse(storedRecentlyViewed);
                    var updatedSlugs = {};

                    slugs = slugs.reverse();

                    slugs.forEach(slug => {
                        updatedSlugs[slug] = {};
                });

                    return updatedSlugs;
                }
            })(),
            }
            },

            created: function () {
                for (slug in this.recentlyViewed) {
                    if (slug) {
                        this.$http(`${this.baseUrl}/product-details/${slug}`)
                            .then(response => {
                            if (response.data.status) {
                            this.$set(this.recentlyViewed, response.data.details.urlKey, response.data.details);
                        } else {
                            delete this.recentlyViewed[response.data.slug];
                            this.$set(this, 'recentlyViewed', this.recentlyViewed);

                            this.$forceUpdate();
                        }
                    })
                    .catch(error => {})
                    }
                }
            },
        })
        })()
    </script>
@endpush
