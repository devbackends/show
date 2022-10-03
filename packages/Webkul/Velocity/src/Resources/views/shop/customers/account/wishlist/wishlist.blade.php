@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('page-detail-wrapper')

<div class="customer-profile__content-wrapper">
    <div class="customer-profile__content-header d-flex">
        <a href="{{ route('customer.account.index') }}" class="customer-profile__content-header-back">
            <i class="far fa-chevron-left"></i>
        </a>
        <h3 class="customer-profile__content-header-title">
            {{ __('shop::app.customer.account.wishlist.title') }}
        </h3>
        @if (count($items))
        <div class="customer-profile__content-header-actions ml-auto">
            <a href="{{ route('customer.wishlist.removeall') }}" class="btn btn-outline-dark">
            <i class="far fa-trash-alt"></i>Delete all
            </a>
        </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

    <div class="account-items-list row wishlist-container">

        @if ($items->count())
        @foreach ($items as $item)
        @php
        $currentMode = $toolbarHelper->getCurrentMode();
        $moveToCartText = __('shop::app.customer.account.wishlist.move-to-cart');
        @endphp

        @include ('shop::products.list.card', [
        'checkmode' => true,
        'moveToCart' => true,
        'addToCartForm' => true,
        'removeWishlist' => true,
        'reloadPage' => true,
        'itemId' => $item->id,
        'product' => $item->product,
        'btnText' => $moveToCartText,
        ])
        @endforeach

        <div class="bottom-toolbar">
            {{ $items->links()  }}
        </div>
        @else
        <div class="empty">
            {{ __('customer::app.wishlist.empty') }}
        </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}

</div>

@endsection