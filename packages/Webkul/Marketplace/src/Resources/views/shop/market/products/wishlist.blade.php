@inject ('wishListHelper', 'Webkul\Marketplace\Helpers\Marketplace')
@php
    $customer = auth('customer')->user();
    $isWished = false;
    $addToWhishlistUrl = '';
    $linkTitle = '';
    if ($customer) {
        $isWished = $wishListHelper->isProductWished($product->product_id, $seller ?? 0);
        $linkTitle = ($isWished) ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');
        $addToWhishlistUrl = ($isWished) ? route('customer.wishlist.remove', $product->product_id) : route('customer.wishlist.add', $product->product_id);
    }
@endphp

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}

    <wishlist-component
        :active="{{$isWished ? 'true' : 'false'}}"
        :is-customer="{{($customer) ? 'true' : 'false'}}"
        product-id="{{ $product->product_id }}"
        seller-id="{{$product->marketplace_seller_id ? $product->marketplace_seller_id : 0}}"
        add-url="{{route('marketplace.wishlist.add')}}"
        remove-url="{{route('marketplace.wishlist.delete')}}"
        add-class-to-link="{{ $addWishlistClass ?? '' }}"
        linkTitle="{{$linkTitle}}"
    ></wishlist-component>

{!! view_render_event('bagisto.shop.products.wishlist.after') !!}