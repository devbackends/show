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
            <div class="row remove-padding-margin">
                <div class="col-12 no-padding">
                    <h3 class="heading">{{ __('velocity::app.products.recently-viewed') }}</h3>
                </div>
            </div>

            <div :class="`recetly-viewed-products-wrapper ${addClassWrapper}`">
                <div
                        :key="Math.random()"
                        class="row small-card-container"
                        v-for="(product, index) in recentlyViewed">

                    <div class="col-3 product-image-container mr15">
                        <a :href="`${baseUrl}/product/${product.urlKey}`" class="unset">
                            <div class="product-image" :style="`background-image: url(${product.image});width:95px;height:95px;`"></div>
                        </a>
                    </div>

                    <div class="col-9 no-padding card-body align-vertical-top" v-if="product.name">
                        <a :href="`${baseUrl}/product/${product.urlKey}`" class="unset no-padding">
                            <div class="product-name">
                                <span class="paragraph regular-font black">@{{ product.name }}</span>
                            </div>

                            <div>
                                <span class="p-18 bold black"  v-html="product.productPrice"></span>
                            </div>

                            <star-ratings v-if="product.rating > 0"
                                          push-class="display-inbl"
                                          :ratings="product.rating">
                            </star-ratings>
                        </a>
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
