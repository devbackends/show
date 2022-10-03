@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')
@php
    $customer = auth('customer')->user();
    $isWished = false;
    $addToWhishlistUrl = '';
    $linkTitle = '';
    if ($customer) {
        $isWished = $wishListHelper->getWishlistProduct($product);
        $linkTitle = ($isWished) ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');
        $addToWhishlistUrl = ($isWished) ? route('customer.wishlist.remove', $product->product_id) : route('customer.wishlist.add', $product->product_id);
    }
@endphp

{!! view_render_event('bagisto.shop.products.wishlist.before') !!}

<wishlist-component
    active="{{$isWished ? 'true' : 'false'}}"
    is-customer="{{($customer) ? 'true' : 'false'}}"
    add-to-whishlist-url="{{$addToWhishlistUrl}}"
    product-id="{{ $product->id }}"
    add-class-to-link="{{ $addWishlistClass ?? '' }}"
    added-text="{{ __('shop::app.customer.account.wishlist.add') }}"
    removed-text="{{ __('shop::app.customer.account.wishlist.remove') }}"
    linkTitle="{{$linkTitle}}">
</wishlist-component>

{!! view_render_event('bagisto.shop.products.wishlist.after') !!}