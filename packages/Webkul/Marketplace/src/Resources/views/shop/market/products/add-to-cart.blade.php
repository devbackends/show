
{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

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

    <div class="product-item__overlay-actions">
        @if (isset($showCompare) && $showCompare)
            <compare-component
                token="{{csrf_token()}}"
                :customer="{{auth()->guard('customer')->user() ? 'true' : 'false'}}"
                product-id="{{ $product->product_id ?? $product }}"
                marketplace-seller-id="{{ $sellerId }}"
                addClass="product-item__overlay-action"
            ></compare-component>
        @endif

        @if (! (isset($showWishlist) && !$showWishlist))
            @include('shop::products.wishlist', [
                'addClass' => $addWishlistClass ?? ''
            ])
        @endif
    </div>
</div>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}
