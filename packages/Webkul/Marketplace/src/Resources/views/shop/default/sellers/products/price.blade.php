{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

@inject ('priceHelper', 'Webkul\Marketplace\Helpers\Price')
<?php

    if (isset($seller)) {
        if ($product->type == 'configurable') {




                $childProduct = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere([
                    'marketplace_seller_id' => $seller->id,
                    'parent_id' => $product->product->id
                ])->pluck('price');

                if (count($childProduct)) {
                    $minPrice = min($childProduct->toArray());
                    $variantMinPrice = core()->currency($minPrice);
                }

        }
    }
?>

<div class="product-price">
    @if ($product->type == 'configurable')
        <span class="price-label">{{ __('shop::app.products.price-label') }}</span>

        @if (isset($seller) && isset($childProduct) && isset($variantMinPrice))
            <span class="final-price">{{ $variantMinPrice }}</span>
        @else
            <span class="final-price">{{ core()->currency($priceHelper->getMinimalPrice($product)) }}</span>
        @endif
    @else
        @if ($priceHelper->haveSpecialPrice($product))
            <div class="sticker sale">
                {{ __('shop::app.products.sale') }}
            </div>

            <span class="regular-price">{{ core()->currency($product->price) }}</span>

            <span class="special-price">{{ core()->currency($priceHelper->getSpecialPrice($product)) }}</span>
        @else
            <span>{{ core()->currency($product->price) }}</span>
        @endif
    @endif
</div>

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}