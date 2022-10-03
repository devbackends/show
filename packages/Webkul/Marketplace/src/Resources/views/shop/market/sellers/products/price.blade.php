{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

@inject ('priceHelper', 'Webkul\Marketplace\Helpers\Price')
<?php

    if (isset($seller)) {
        if ($product->type == 'configurable') {

                $childProduct = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere([
                    'marketplace_seller_id' => $seller->id,
                    'parent_id' => $product->id
                ])->pluck('price');

                if (count($childProduct)) {
                    $minPrice = min($childProduct->toArray());
                    $variantMinPrice = core()->currency($minPrice);
                }

        }
    }

?>

<div class="price my-3">
    @if ($product->type == 'configurable')
        <span class="price-label font-weight-bold">{{ __('shop::app.products.price-label') }}</span>

        @if (isset($seller) && isset($childProduct) && isset($variantMinPrice))
            <p class="h2 mb-1 final-price">{{ $variantMinPrice }}</p>
        @else
            <p class="h2 mb-1 final-price">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</p>
        @endif
    @else
        @if ($priceHelper->haveSpecialPrice($product))
            <div class="badge badge-primary text-uppercase mb-2">
                {{ __('shop::app.products.sale') }}
            </div>
            <p class="h2 mb-0 special-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</p>
            <div class="price__list">
                <span class="before regular-price">Before {{ core()->currency($product->price) }}</span><span class="after">You save {{ core()->currency($product->price - $priceHelper->getSpecialPrice($product)) }}</span>
            </div>
        @else
            <p class="h2">{{ core()->currency($product->price) }}</p>
        @endif
    @endif
</div>


{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}