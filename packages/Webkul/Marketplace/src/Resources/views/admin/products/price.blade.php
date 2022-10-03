<div class="product-price">
    @if ($product->type == 'configurable')
        <span class="price-label">{{ __('shop::app.products.price-label') }}</span>

        <span class="final-price">{{ core()->currency($product->getTypeInstance()->getMinimalPrice($product)) }}</span>
    @else
        @if ($product->getTypeInstance()->haveSpecialPrice($product))

            <div class="sticker sale">
                {{ __('shop::app.products.sale') }}
            </div>

            <span class="regular-price">{{ core()->currency($product->price) }}</span>

            <span class="special-price">{{ core()->currency($product->getTypeInstance()->getSpecialPrice($product)) }}</span>
        @else
            <span>{{ core()->currency($product->price) }}</span>
        @endif
    @endif
</div>