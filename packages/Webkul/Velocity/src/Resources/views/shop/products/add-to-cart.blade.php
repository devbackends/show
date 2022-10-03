{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

    <div>
        <div class="add-to-cart-btn">
            @if (isset($form) && !$form)
                <button
                    type="submit"
                    {{ ! $product->isSaleable() ? 'disabled' : '' }}
                    class="btn btn-primary {{ $addToCartBtnClass ?? '' }}">

                    @if (! (isset($showCartIcon) && !$showCartIcon))
                        <span class="icon add-to-cart-custom-icon"></span>
                    @endif

                        {{ __('shop::app.products.add-to-cart') }}
                </button>
            @elseif(isset($addToCartForm) && !$addToCartForm)
                <form
                    method="POST"
                    action="{{ route('cart.add', $product->product_id) }}">

                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button
                        type="submit"
                        {{ ! $product->isSaleable() ? 'disabled' : '' }}
                        class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                        @if (! (isset($showCartIcon) && !$showCartIcon))
                            <i class="far fa-cart-plus"></i>
                        @endif

                        <span class="fs14 fw6 text-uppercase text-up-4">
                            {{ $btnText ?? __('shop::app.products.add-to-cart') }}
                        </span>
                    </button>
                </form>
            @else
                <add-to-cart
                    form="true"
                    csrf-token='{{ csrf_token() }}'
                    product-flat-id="{{ $product->id }}"
                    product-id="{{ $product->product_id }}"
                    reload-page="{{ $reloadPage ?? false }}"
                    move-to-cart="{{ $moveToCart ?? false }}"
                    add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                    is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                    show-cart-icon={{ !(isset($showCartIcon) && !$showCartIcon) }}
                    btn-text="{{ (!$product->isSaleable()) ? __('shop::app.products.out-of-stock') : $btnText ?? __('shop::app.products.add-to-cart') }}">
                </add-to-cart>
            @endif
        </div>
        @if (isset($showCompare) && $showCompare)
            <compare-component
                token="{{csrf_token()}}"
                :customer="{{auth()->guard('customer')->user() ? 'true' : 'false'}}"
                product-id="{{ $product->id }}"
            ></compare-component>
        @endif

        @if (! (isset($showWishlist) && !$showWishlist))
            @include('shop::products.wishlist', [
                'addClass' => $addWishlistClass ?? ''
            ])
        @endif
    </div>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}