@extends('marketplace::shop.layouts.master')

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : str_limit(strip_tags($product->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords }}"/>
@stop

@section('content-wrapper')

    <?php
        $baseProduct = $product->parent_id ? $product->parent : $product;
        $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository');
    ?>

    {!! view_render_event('bagisto.shop.sellers.products.offers.before', ['product' => $product]) !!}

    <div class="product-offer-container">

        <div class="product">
            <div class="product-information">

                <?php $productBaseImage = app('Webkul\Product\Helpers\ProductImage')->getProductBaseImage($product); ?>

                <div class="product-logo-block">
                    <a href="{{ route('shop.product.index', $baseProduct->url_key) }}" title="{{ $baseProduct->name }}">
                        <img src="{{ $productBaseImage['medium_image_url'] }}" />
                    </a>
                </div>

                <div class="product-information-block">
                    <a href="{{ route('shop.product.index', $baseProduct->url_key) }}" class="product-title">
                        {{ $baseProduct->name }}
                    </a>

                    <div class="price">
                        @include ('shop::products.price', ['product' => $product])
                    </div>

                    @include ('shop::products.view.stock', ['product' => $product])

                    <?php $attributes = []; ?>

                    @if ($baseProduct->type == 'configurable')

                        <div class="options">
                            <?php $options = []; ?>

                            @foreach ($baseProduct->super_attributes as $attribute)

                                @foreach ($attribute->options as $option)

                                    @if ($product->{$attribute->code} == $option->id)

                                        <?php $attributes[$attribute->id] = $option->id; ?>

                                        <?php array_push($options, $attribute->name . ' : ' . $option->label); ?>

                                    @endif

                                @endforeach

                            @endforeach

                            {{ implode(', ', $options) }}

                        </div>

                    @endif

                </div>
            </div>

            <div class="review-information">

                @include ('shop::products.review', ['product' => $baseProduct])

            </div>
        </div>

        <div class="seller-product-list padding-15">
            <h2 class="heading">{{ __('marketplace::app.shop.products.more-sellers') }}</h2>

            <div class="content">

                @foreach ($productFlatRepository->getSellerProducts($product) as $productFlat)
                    <form action="{{ route('cart.add', $baseProduct->id) }}" method="POST">

                        @csrf()
                        <input type="hidden" name="product_id" value="{{ $productFlat->product_id }}">
                        <input type="hidden" name="seller_info[product_id]" value="{{ $productFlat->product_id }}">
                        <input type="hidden" name="seller_info[seller_id]" value="{{ $productFlat->seller->id }}">
                        <input type="hidden" name="seller_info[is_owner]" value="0">

                        @if ($baseProduct->type == 'configurable')
                            <input type="hidden" name="selected_configurable_option" value="{{ $product->id }}">

                            @foreach ($attributes as $attributeId => $optionId)
                                <input type="hidden" name="super_attribute[{{$attributeId}}]" value="{{$optionId}}"/>
                            @endforeach
                        @endif

                        <div class="seller-product-item">

                            <div class="product-info-top table">

                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="profile-logo-block">
                                                    @if ($logo = $productFlat->seller->logo_url)
                                                        <img src="{{ $logo }}" />
                                                    @else
                                                        <img src="{{ bagisto_asset('images/default-logo.svg') }}" />
                                                    @endif
                                                </div>

                                                <div class="profile-information-block">
                                                    <a href="{{ route('marketplace.seller.show', $productFlat->seller->url) }}" class="shop-title">
                                                        {{ $productFlat->seller->shop_title }}
                                                    </a>

                                                    <div class="review-information">

                                                        <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

                                                        <span class="stars">

                                                        <star-ratings
                                                            ratings="{{ ceil($reviewRepository->getAverageRating($productFlat->seller)) }}"
                                                            push-class="mr5"
                                                        ></star-ratings>

                                                            {{
                                                                __('marketplace::app.shop.products.seller-total-rating', [
                                                                        'avg_rating' => $reviewRepository->getAverageRating($productFlat->seller),
                                                                        'total_rating' => $reviewRepository->getTotalRating($productFlat->seller),
                                                                    ])
                                                            }}
                                                        </span>

                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @if ($productFlat->condition == 'new')
                                                    {{ __('marketplace::app.shop.products.new') }}
                                                @else
                                                    {{ __('marketplace::app.shop.products.used') }}
                                                @endif
                                            </td>

                                            <td>
                                                <div class="product-price">
                                                    @if ($product->getTypeInstance()->haveSpecialPrice($productFlat))
                                                        <div class="sticker sale">
                                                            {{ __('shop::app.products.sale') }}
                                                        </div>

                                                        <span class="regular-price">{{ core()->currency($productFlat->price) }}</span>

                                                        <span class="special-price">{{ core()->currency($product->getTypeInstance()->getSpecialPrice($productFlat)) }}</span>
                                                    @else
                                                        <span>{{ core()->currency($productFlat->price) }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                <div class="control-group">
                                                    <input type="text" name="quantity" value="1" class="control">
                                                </div>

                                                @if ($productFlat->haveSufficientQuantity(1))

                                                    <button type="submit" class="btn btn-black btn-lg theme-btn">
                                                        {{ __('marketplace::app.shop.products.add-to-cart') }}
                                                    </button>
                                                @else

                                                    <div class="stock-status">
                                                        {{ __('marketplace::app.shop.products.out-of-stock') }}
                                                    </div>

                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <div class="product-info-bottom">
                                <?php $baseSellerProduct = $product->parent_id ? $product->parent : $product; ?>
                                <div class="product">
                                    <div class="product-information">

                                        <?php $productImages = app('Webkul\Product\Helpers\ProductImage')->getGalleryImages($baseSellerProduct); ?>

                                        <div class="row col-12">
                                            <product-gallery></product-gallery>
                                        </div>

                                        <div class="product-information-block">

                                            {{ $baseSellerProduct->description }}

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </form>

                @endforeach

            </div>

        </div>

    </div>

    {!! view_render_event('bagisto.shop.sellers.products.offers.after', ['product' => $product]) !!}

@endsection

<script type="text/x-template" id="product-gallery-template">
    <ul class="thumb-list col-12 row ltr" type="none">
        @if (sizeof($productImages) > 4)
            <li class="arrow left" @click="scroll('prev')">
                <i class="rango-arrow-left fs24"></i>
            </li>
        @endif

        <carousel-component
            slides-per-page="4"
            :id="galleryCarouselId"
            pagination-enabled="hide"
            navigation-enabled="hide"
            add-class="product-gallary"
            :slides-count="{{ sizeof($productImages) }}">

            @foreach ($productImages as $index => $thumb)
                <slide :slot="`slide-0`">
                    <img style="padding: 0px 67px 0px 10px;" src="{{$thumb['small_image_url']}}" />
                </slide>
            @endforeach
        </carousel-component>

        @if (sizeof($productImages) > 4)
            <li class="arrow right" @click="scroll('next')">
                <i class="rango-arrow-right fs24"></i>
            </li>
        @endif
    </ul>
</script>

@push('scripts')
    <script type="text/javascript">
        (() => {
            var galleryImages = @json($productImages);

            Vue.component('product-gallery', {
                template: '#product-gallery-template',
                data: function() {
                    return {
                        images: galleryImages,
                        thumbs: [],
                        galleryCarouselId: 'product-gallery-carousel',
                        currentLargeImageUrl: '',
                        currentOriginalImageUrl: '',
                        counter: {
                            up: 0,
                            down: 0,
                        }
                    }
                },

                watch: {
                    'images': function(newVal, oldVal) {
                        this.changeImage({
                            largeImageUrl: this.images[0]['large_image_url'],
                            originalImageUrl: this.images[0]['original_image_url'],
                        })

                        this.prepareThumbs()
                    }
                },

                created: function() {
                    this.changeImage({
                        largeImageUrl: this.images[0]['large_image_url'],
                        originalImageUrl: this.images[0]['original_image_url'],
                    })

                    this.prepareThumbs()
                },

                methods: {
                    prepareThumbs: function() {
                        this.thumbs = [];

                        this.images.forEach(image => {
                            this.thumbs.push(image);
                        });
                    },

                    changeImage: function({largeImageUrl, originalImageUrl}) {
                        this.currentLargeImageUrl = largeImageUrl;

                        this.currentOriginalImageUrl = originalImageUrl;

                        this.$root.$emit('changeMagnifiedImage', {
                            smallImageUrl: this.currentOriginalImageUrl
                        });

                        let productImage = $('.vc-small-product-image');
                        if (productImage && productImage[0]) {
                            productImage = productImage[0];

                            productImage.src = this.currentOriginalImageUrl;
                        }
                    },

                    scroll: function (navigateTo) {
                        let navigation = $(`#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`);

                        if (navigation && (navigation = navigation[0])) {
                            navigation.click();
                        }
                    },
                }
            });
        })()
    </script>
@endpush