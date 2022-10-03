{{--

{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

<button type="submit" class="btn btn-primary addtocart" {{ ! $product->isSaleable() ? 'disabled' : '' }}>
    {{ __('shop::app.products.add-to-cart') }}
</button>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}--}}





<div>
    @php
        $sellerId = 0;
        $sellerInfo = [];
        if (isset($product->marketplace_seller_id)) {
                $sellerInfo = [
                    'sellerId' => $product->marketplace_seller_id,
                    'productId' => $product->product_id,
                    'isOwner' => 1
                ];
        }

    @endphp
    <add-to-cart
            csrf-token='{{ csrf_token() }}'
            product-flat-id="{{ $product->id }}"
            product-id="{{ $product->product_id }}"
            reload-page="{{ $reloadPage ?? false }}"
            move-to-cart="{{ $moveToCart ?? false }}"
            is-enable="{{ $product->isSaleable($sellerId) ? 'true' : 'false' }}"
            btn-text="{{ (!$product->isSaleable($sellerId)) ? __('shop::app.products.sold-out') : $btnText ?? __('shop::app.products.add-to-cart') }}"
            @if(!empty($sellerInfo)) :seller-info='@json($sellerInfo)' @endif
    ></add-to-cart>


</div>


