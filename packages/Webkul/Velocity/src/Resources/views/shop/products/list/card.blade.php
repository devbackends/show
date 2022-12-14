@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
{{-- @include('shop::UI.product-quick-view') --}}

@push('css')
<style type="text/css">
    .list-card .wishlist-icon i {
        padding-left: 10px;
    }

    .product-price span:first-child,
    .product-price span:last-child {
        font-size: 18px;
        font-weight: 600;
    }
</style>
@endpush

@php
if (isset($checkmode) && $checkmode && $toolbarHelper->getCurrentMode() == "list") {
$list = true;
}
@endphp

@php
$productBaseImage = $productImageHelper->getProductBaseImage($product);
$totalReviews = $reviewHelper->getTotalReviews($product);
$avgRatings = ceil($reviewHelper->getAverageRating($product));
@endphp

{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $product]) !!}
@if (isset($list) && $list)
<div class="col-12 lg-card-container list-card product-card row">
    <div class="product-image">
        <a title="{{ $product->name }}" href="{{ route('shop.product.index', $product->url_key) }}">

            <img src="{{ $productBaseImage['medium_image_url'] }}" :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />
        </a>
    </div>

    <div class="product-information">
        <div>
            <div class="product-name">
                <a href="{{ route('shop.product.index', $product->url_key) }}" title="{{ $product->name }}" class="unset">

                    <span class="fs16">{{ $product->name }}</span>
                </a>
            </div>

            <div class="product-price">
                @include ('shop::products.price', ['product' => $product])
            </div>

            @if( $totalReviews )
            <div class="product-rating">
                <star-ratings ratings="{{ $avgRatings }}"></star-ratings>
                <span>{{ $totalReviews }} Ratings</span>
            </div>
            @endif

            <div class="cart-wish-wrap mt5">
                @include ('shop::products.add-to-cart', [
                'product' => $product,
                'showCompare' => true,
                'addWishlistClass' => 'pl10',
                'addToCartBtnClass' => 'medium-padding'
                ])
            </div>
        </div>
    </div>
</div>
@else
<div class="col-md-3">
    <div class="card grid-card product-card product-card-new custom-products-list">

        <a href="{{ route('shop.product.index', $product->url_key) }}" title="{{ $product->name }}" class="product-image-container">

            <img loading="lazy" class="card-img-top" alt="{{ $product->name }}" src="{{ $productBaseImage['large_image_url'] }}" :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" />

            @if(!empty($product->new) && $product->new==1 )
            <span class="new-product-flag">NEW</span>
            @endif

            {{-- <product-quick-view-btn :quick-view-details="product"></product-quick-view-btn>--}}
        </a>

        <div class="card-body">
            <div class="product-name col-12 no-padding">
                <a href="{{ route('shop.product.index', $product->url_key) }}" title="{{ $product->name }}" class="unset">

                    <span class="fs16 paragraph gray-dark">{{ $product->name }}</span>
                </a>
            </div>

            <div class="product-weight col-12 no-padding" style="height: 22px;">
                @if(!empty($product->weight))
                <span class="fs16 paragraph gray-dark">{{ $product->weight }} lbs</span>
                @endif
            </div>

            <div class="product-price fs16 paragraph-big bold">
                @include ('shop::products.price', ['product' => $product])
            </div>

            @if ($totalReviews)
            <div class="product-rating col-12 no-padding">
                <star-ratings ratings="{{ $avgRatings }}"></star-ratings>
                <span class="align-top">
                    {{ __('velocity::app.products.ratings', ['totalRatings' => $totalReviews ]) }}
                </span>
            </div>
            @else
            {{-- <div class="product-rating col-12 no-padding">
                                        <span class="fs14">{{ __('velocity::app.products.be-first-review') }}</span>
        </div>--}}
        @endif
        <div class="cart-wish-wrap no-padding ml0">
            @include ('shop::products.add-to-cart', [
            'showCompare' => true,
            'product' => $product,
            'btnText' => $btnText ?? null,
            'moveToCart' => $moveToCart ?? null,
            'reloadPage' => $reloadPage ?? null,
            'addToCartForm' => $addToCartForm ?? false,
            'addToCartBtnClass' => $addToCartBtnClass ?? '',
            ])
        </div>
    </div>
</div>
</div>
@endif

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}