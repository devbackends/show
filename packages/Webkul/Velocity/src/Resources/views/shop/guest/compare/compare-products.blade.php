@php
    $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeRepository');
    $comparableAttributes = $attributeRepository->findByField('is_comparable', 1);

@endphp

@push('scripts')
    <script type="text/x-template" id="compare-product-template">
        <section class="customer-profile cart-details">
            <h3 class="">
                {{ __('velocity::app.customer.compare.compare_similar_items') }}
            </h3>

            {{--            <div class="col-6" v-if="products.length > 0">
                            <button
                                class="theme-btn light pull-right"
                                @click="removeProductCompare('all')">
                                {{ __('shop::app.customer.account.wishlist.deleteall') }}
                            </button>
                        </div>--}}

            {!! view_render_event('bagisto.shop.customers.account.compare.view.before') !!}

            <div v-if="isProductListLoaded && products.length > 0" class="scroll-container">
                <span id="scroll-left" class="table-scrol-left-icon"></span>
                <span id="scroll-right" class="table-scrol-right-icon"></span>
            </div>


            <table class="row compare-products">
                <shimmer-component v-if="!isProductListLoaded && !isMobile()"></shimmer-component>

                <template v-else-if="isProductListLoaded && products.length > 0">


                    @php
                        $comparableAttributes = $comparableAttributes->toArray();

                        array_splice($comparableAttributes, 1, 0, [[
                            'code' => 'image',
                            'admin_name' => ''
                        ]]);
                        array_splice($comparableAttributes, 2, 0, [[
                            'code' => 'new',
                            'admin_name' => 'condition'
                        ]]);

                         array_splice($comparableAttributes, 3, 0, [[
                            'code' => 'product_price',
                            'admin_name' => 'Price'
                        ]]);

                        array_splice($comparableAttributes, 4, 0, [[
                            'code' => 'addToCartHtml',
                            'admin_name' => ''
                        ]]);


                    @endphp

                    @foreach ($comparableAttributes as $attribute)

                        @if($attribute['code']=='name' || $attribute['code']=='new' || $attribute['code']=='image' ||  $attribute['code']=='addToCartHtml' || $attribute['code']=='product_price')
                            @if($attribute['code']=='name')
                                @php    $attribute['admin_name']='Product'; @endphp
                            @endif
                            <tr>
                                <td>
                                    <span class="paragraph bold black">{{ $attribute['admin_name'] }}</span>
                                </td>

                                <td :key="`title-${index}`" v-for="(product, index) in products">

                                    @switch ($attribute['code'])
                                        @case('name')
                                        <a :href="`${$root.baseUrl}/product/${product.url_key}`" class="unset remove-decoration active-hover">
                                            <h4 v-text="product['{{ $attribute['code'] }}']"></h4>
                                        </a>
                                        @break

                                        @case('image')
                                        <a :href="`${$root.baseUrl}/product/${product.url_key}`" class="unset">
                                            <img
                                                    class="image-wrapper"
                                                    :src="product['{{ $attribute['code'] }}']"
                                                    :onerror="`this.src='${$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />
                                        </a>
                                        @break

                                        @case('new')
                                        <div class="align-center d-block"><span v-if="product['new']==1" class="new-product-flag-r" style="left: 40%;">NEW</span></div>
                                        @break

                                        @case('product_price')
                                        <h3 class="customer-profile__compare-price" v-html="'$ ' + product['price']"> </h3>
                                        @break

                                        @case('addToCartHtml')
                                        <div class="customer-profile__compare-action">
                                            <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>

                                            <i
                                                    class="far fa-times customer-profile__compare-action-close"
                                                    @click="removeProductCompare(product.id)"></i>
                                        </div>
                                        @break

                                        @case('color')
                                        <span v-html="product.color_label" class="fs16"></span>
                                        @break

                                        @case('size')
                                        <span v-html="product.size_label" class="fs16"></span>
                                        @break

                                        @case('description')
                                        <span v-html="product.description"></span>
                                        @break

                                        @default
                                        @switch ($attribute['type'])
                                            @case('boolean')
                                            <span
                                                    v-text="product.product['{{ $attribute['code'] }}']
                                                            ? '{{ __('velocity::app.shop.general.yes') }}'
                                                            : '{{ __('velocity::app.shop.general.no') }}'"
                                            ></span>
                                            @break;
                                            @default
                                            <span v-html="product['{{ $attribute['code'] }}'] ? product['{{ $attribute['code'] }}'] : product.product['{{ $attribute['code'] }}'] ? product.product['{{ $attribute['code'] }}'] : '__'" class="fs16"></span>
                                            @break;
                                        @endswitch

                                        @break

                                    @endswitch
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </template>

                <span v-else-if="isProductListLoaded && products.length == 0">
                    @{{ __('customer.compare.empty-text') }}
                </span>
            </table>

            {!! view_render_event('bagisto.shop.customers.account.compare.view.after') !!}
        </section>
    </script>

    <script>
        Vue.component('compare-product', {
            template: '#compare-product-template',

            data: function () {
                return {
                    'products': [],
                    'isProductListLoaded': false,
                    'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            mounted: function () {
                this.getComparedProducts();
            },

            methods: {
                'getComparedProducts': function () {
                    let items = '';
                    let url = `${this.$root.baseUrl}/${this.isCustomer ? 'comparison' : 'detailed-products'}`;



                    let data = {
                        params: {'data': true}
                    }

                    if (! this.isCustomer) {
                        items = this.getStorageValue('compared_product');
                        items = items ? items.join('&') : '';

                        data = {
                            params: {
                                items
                            }
                        };
                    }

                    if (this.isCustomer || (! this.isCustomer && items != "")) {
                        this.$http.get(url, data)
                            .then(response => {
                            this.isProductListLoaded = true;
                        this.products = response.data.products;
                    })
                    .catch(error => {
                            this.isProductListLoaded = true;
                        console.log(this.__('error.something_went_wrong'));
                    });
                    } else {
                        this.isProductListLoaded = true;
                    }

                },

                'removeProductCompare': function (productId) {
                    if (this.isCustomer) {
                        this.$http.delete(`${this.$root.baseUrl}/comparison?productId=${productId}`)
                            .then(response => {
                            if (productId == 'all') {
                            this.$set(this, 'products', this.products.filter(product => false));
                        } else {
                            this.$set(this, 'products', this.products.filter(product => product.id != productId));
                        }

                        window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                    })
                    .catch(error => {
                            console.log(this.__('error.something_went_wrong'));
                    });
                    } else {
                        let existingItems = this.getStorageValue('compared_product');

                        if (productId == "all") {
                            updatedItems = [];
                            this.$set(this, 'products', []);
                        } else {
                            updatedItems = existingItems.filter(item => item != productId);
                            this.$set(this, 'products', this.products.filter(product => product.id != productId));
                        }

                        this.setStorageValue('compared_product', updatedItems);

                        window.showAlert(
                            `alert-success`,
                            this.__('shop.general.alert.success'),
                            `${this.__('customer.compare.removed')}`
                        );
                    }

                    this.$root.headerItemsCount++;
                },
            }
        });
    </script>
    <script>
        $( document ).ready(function() {
            var scroll=0;

            $(document).on('click','#scroll-left',function(e){
                if(scroll >= 200 ){ scroll-=200;
                    var leftPos = $('.compare-products').scrollLeft(scroll);
                    /*$(".compare-products").animate({scrollLeft: scroll}, 800);*/
                }

            });
            $(document).on('click','#scroll-right',function(e){

                if(scroll < $('.compare-products').width()+ 160){
                    scroll+=200;
                    var rightPos = $('.compare-products').scrollLeft(scroll);
                    /*$(".compare-products").animate({scrollLeft: scroll}, 800);*/
                }


            });
        });
    </script>
@endpush