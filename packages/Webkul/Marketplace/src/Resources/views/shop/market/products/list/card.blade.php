@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('wishListHelper', 'Webkul\Marketplace\Helpers\Marketplace')
{{-- @include('shop::UI.product-quick-view') --}}

@push('css')

@endpush



@php
    $price = $product->getTypeInstance()->getPriceHtml();

    $sellerInfo = [];

            $sellerInfo = [
                'sellerId' => $product->marketplace_seller_id,
                'productId' => $product->product_id,
                'isOwner' => 1
            ];


    $seller = (isset($seller)) ? $seller : 0;
    $productBaseImage = $productImageHelper->getProductBaseImage($product);
    $totalReviews = $reviewHelper->getTotalReviews($product);
    $avgRatings = ceil($reviewHelper->getAverageRating($product));
    $productUrl = (isset($seller) && $seller) ? route('shop.sellerProduct.index', [
        'seller' => $seller,
        'slug' => $product->url_key,
    ]) : route('shop.product.index', $product->url_key);

    $customer = auth('customer')->user();
    $isWished = false;
    $linkTitle='';
    if ($customer) {
        $isWished = $wishListHelper->isProductWished($product->product_id, $seller);
        $linkTitle = ($isWished) ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');
    }
@endphp
{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $product]) !!}

    <!-- PRODUCT LIST ITEM -->
    <div class="product-item card">
        <div class="product-item__image card-img-top">

            <a href="{{ $productUrl }}" title="{{ $product->name }}"
               class="">
                <img src="{{ $productBaseImage['large_image_url'] }}"
                     :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`"
                     alt="{{ $product->name }}">

                @if(isset($product->isWithinThirtyDays))
                    @if(!empty($product->isWithinThirtyDays) && $product->isWithinThirtyDays==1 )
                        <span class="badge badge-dark">New</span>
                    @endif
                @endif
            </a>

            <div class="product-item__overlay-actions">
                <wishlist-component
                    :active="{{$isWished ? 'true' : 'false'}}"
                    :is-customer="{{($customer) ? 'true' : 'false'}}"
                    :product-id="{{$product->product_id}}"
                    seller-id="{{$seller}}"
                    add-url="{{route('marketplace.wishlist.add')}}"
                    remove-url="{{route('marketplace.wishlist.delete')}}"
                    add-class-to-link="{{ $addWishlistClass ?? '' }}"
                    linkTitle="{{$linkTitle}}">
                </wishlist-component>

                <compare-component
                    token="{{csrf_token()}}"
                    :customer="{{auth()->guard('customer')->user() ? 'true' : 'false'}}"
                    product-id="{{ $product->product_id ?? $product->id }}"
                    marketplace-seller-id="{{ $seller }}"
                ></compare-component>

            </div>

        </div>
        <div class="product-item__body card-body">

            @if(!empty($product->new) && $product->new==1 )
                {{--<span class="badge badge-dark">New</span>--}}
            @endif


            <a href="{{ $productUrl }}" title="{{ $product->name }}"><h3 class="name">{{ $product->name }}</h3></a>
            <div class="description"><strong></strong></div> {{--here set distance and seller shop title--}}
            <span class="price">{!! $price !!}</span>
        </div>

        {!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

        <add-to-cart
            csrf-token='{{ csrf_token() }}'
            product-flat-id="{{ $product->id }}"
            product-id="{{ $product->product_id }}"
            reload-page="{{ $reloadPage ?? false }}"
            move-to-cart="{{ $moveToCart ?? false }}"
            is-enable="{{ $product->isSaleable($seller) ? 'true' : 'false' }}"
            btn-text="{{ (!$product->isSaleable($seller)) ? __('shop::app.products.out-of-stock') : $btnText ?? __('shop::app.products.add-to-cart') }}"
            @if(!empty($sellerInfo)) :seller-info='@json($sellerInfo)' @endif
        ></add-to-cart>

    </div>
    <!-- END PRODUCT LIST ITEM -->

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}