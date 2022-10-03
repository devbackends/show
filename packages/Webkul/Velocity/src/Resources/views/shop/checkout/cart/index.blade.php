@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')
    <cart-component></cart-component>
@endsection

@push('css')
    <style type="text/css">
        .quantity {
            width: unset;
            float: right;
        }
    </style>
@endpush

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script type="text/x-template" id="cart-template">
        <div class="container">
            <section class="cart-details row no-margin col-12">
                <h3 class="heading col-12">{{ __('shop::app.checkout.cart.title') }}</h3>

                @if ($cart)
                    <div class="cart-details-header col-lg-8 col-md-12">
                        <div class="row cart-header col-12 no-padding">
                            <span class="col-8 fw6 fs16 pr0">
                                Products
                            </span>

                            <span class="col-2 fw6 fs16 no-padding text-left">
                                Qunatity
                            </span>

                            <span class="col-2 fw6 fs16 text-left pr0">
                                {{ __('velocity::app.checkout.subtotal') }}
                            </span>
                        </div>

                        <div class="cart-content col-12">
                            <form
                                action="{{ route('shop.checkout.cart.update') }}"
                                method="POST"
                                @submit.prevent="onSubmit">

                                <div class="cart-item-list">
                                    @csrf

                                    @foreach ($cart->items as $key => $item)

                                        @php
                                            $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);
                                            $product = $item->product;

                                            $productPrice = $product->getTypeInstance()->getProductPrices();

                                        @endphp

                                        <div class="row" v-if="!isMobileDevice">
                                            <div class="borderTop"></div>
                                            <a
                                                title="{{ $product->name }}"
                                                class="product-image-container col-12 col-md-3"
                                                href="{{ route('shop.product.index', $product->url_key) }}">

                                                <img
                                                    class="card-img-top"
                                                    alt="{{ $product->name }}"
                                                    src="{{ $productBaseImage['large_image_url'] }}"
                                                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`">
                                            </a>

                                            <div class="product-details-content col-12 col-md-6 pr0">
                                                <div class="row item-title no-margin">
                                                    <a
                                                        href="{{ route('shop.product.index', $product->url_key) }}"
                                                        title="{{ $product->name }}"
                                                        class="unset col-12 no-padding">

                                                        <p  class="link-color">{{ $product->name }}</p>
                                                    </a>
                                                </div>

                                                @if (isset($item->additional['attributes']))
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        <div class="row col-12 no-padding no-margin display-block">
                                                            <label class="no-margin">
                                                                {{ $attribute['attribute_name'] }} :
                                                            </label>
                                                            <span>
                                                                {{ $attribute['option_label'] }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <div class="row col-12 no-padding no-margin">
                                                    @include ('shop::products.price', ['product' => $product])
                                                </div>

                                                @php
                                                    $moveToWishlist = trans('shop::app.checkout.cart.move-to-wishlist');
                                                @endphp

                                                <div class="no-padding col-12 cursor-pointer fs16">
                                                    @auth('customer')
                                                        @if ($item->parent_id != 'null' ||$item->parent_id != null)
                                                            @include('shop::products.wishlist', [
                                                                'route' => route('shop.movetowishlist', $item->id),
                                                                'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                            ])
                                                        @else
                                                            @include('shop::products.wishlist', [
                                                                'route' => route('shop.movetowishlist', $item->child->id),
                                                                'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                            ])
                                                        @endif
                                                    @endauth

                                                    @guest('customer')
                                                        @include('shop::products.wishlist', [
                                                            'isMoveToWishlist' => route('shop.checkout.cart.remove', ['id' => $item->id]),
                                                            'text' => "<span class='align-vertical-top'>$moveToWishlist</span>"
                                                        ])
                                                    @endguest

                                                    <a
                                                        class="unset
                                                            @auth('customer')
                                                                ml10
                                                            @endauth
                                                        "
                                                        href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}"
                                                        onclick="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">

                                                        <span class="icon custom-delete-icon align-middle"></span>
                                                        <span class="align-middle">{{ __('shop::app.checkout.cart.remove') }}</span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="product-quantity col-6 col-md-2 no-padding">
                                                <quantity-changer
                                                    :control-name="'qty[{{$item->id}}]'"
                                                    quantity="{{ $item->quantity }}">
                                                </quantity-changer>
                                            </div>

                                            <div class="product-price fs18 col-6 col-md-1">
                                                <span class="card-current-price fw6 mr10">
                                                    {{ core()->currency( $item->base_total) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row" v-else>
                                            <a
                                                title="{{ $product->name }}"
                                                class="product-image-container col-12 col-md-2"
                                                href="{{ route('shop.product.index', $product->url_key) }}">

                                                <img
                                                    src="{{ $productBaseImage['medium_image_url'] }}"
                                                    class="card-img-top"
                                                    alt="{{ $product->name }}">
                                            </a>

                                            <div class="col-12 col-md-10 pr0 item-title">
                                                <a
                                                    href="{{ route('shop.product.index', $product->url_key) }}"
                                                    title="{{ $product->name }}"
                                                    class="unset col-12 no-padding">

                                                    <h3  class="heading link-color">{{ $product->name }}</h3>
                                                </a>

                                                @if (isset($item->additional['attributes']))
                                                    <div class="row col-12 no-padding no-margin">

                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach

                                                    </div>
                                                @endif

                                                <div class="col-12 no-padding">
                                                    @include ('shop::products.price', ['product' => $product])
                                                </div>

                                                <div class="row col-12 remove-padding-margin actions">
                                                    <div class="product-quantity col-lg-4 col-6 no-padding">
                                                        <quantity-changer
                                                            :control-name="'qty[{{$item->id}}]'"
                                                            quantity="{{ $item->quantity }}">
                                                        </quantity-changer>
                                                    </div>

                                                    <div class="col-4 cursor-pointer text-down-4">
                                                        <a href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}" class="unset">
                                                            <i class="material-icons fs24">delete</i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    @endforeach
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}

                                       <div class="d-flex">
                                            <a style="font-size: 18px;"
                                                    class="link-color remove-decoration fs16 no-padding paragraph bold mr-auto"
                                                    href="{{ route('shop.home.index') }}">
                                                    <span class="icon shopping-icon"></span>{{ __('shop::app.checkout.cart.continue-shopping') }}
                                            </a>
                                            <button
                                                type="submit"
                                                class="theme-btn light pull-right unset ml-auto">
                                                {{ __('shop::app.checkout.cart.update-cart') }}
                                            </button>
                                       </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                            </form>
                        </div>

                        @include ('shop::products.view.cross-sells')
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

                @if ($cart)
                    <div class="col-lg-4 col-md-12 offset-lg-2 row order-summary-container">
                        <div class="order-summary fs16">

                            {{--<h3 class="fw6">{{ __('velocity::app.checkout.cart.cart-summary') }}</h3>--}}

                            <div class="row">
                                <span class="paragraph col-8 no-padding-sides">{{ __('velocity::app.checkout.sub-total') }}</span>
                                <span class="paragraph col-4 no-padding-sides text-right">{{ $cart->base_sub_total }}</span>
                            </div>

                            @if ($cart->selected_shipping_rate)
                                <div class="row">
                                    <span class="paragraph col-8 no-padding-sides">{{ __('shop::app.checkout.total.delivery-charges') }}</span>
                                    <span class="paragraph col-4 no-padding-sides text-right">{{ $cart->selected_shipping_rate->base_price }}</span>
                                </div>
                            @endif

                            @if ($cart->base_tax_total)
                                @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($cart, true) as $taxRate => $baseTaxAmount )
                                    <div class="row">
                                        <span class="paragraph col-8 no-padding-sides" id="taxrate-{{ core()->taxRateAsIdentifier($taxRate) }}">{{ __('shop::app.checkout.total.tax') }} {{ $taxRate }} %</span>
                                        <span class="paragraph col-4 no-padding-sides text-right" id="basetaxamount-{{ $taxRate }}">{{ $baseTaxAmount }}</span>
                                    </div>
                                @endforeach
                            @endif

                            @if (
                                $cart->base_discount_amount
                                && $cart->base_discount_amount > 0
                            )
                                <div
                                        id="discount-detail"
                                        class="row">

                                    <span class="paragraph  col-8 no-padding-sides">{{ __('shop::app.checkout.total.disc-amount') }}</span>
                                    <span class="paragraph col-4 no-padding-sides text-right">-{{ $cart->base_discount_amount }}</span>  {{--core()->currency($cart->base_discount_amount)--}}
                                </div>
                            @endif

                            <div class="payable-amount row" id="grand-total-detail">
                                <span class="paragraph bold col-8 no-padding-sides">Total</span>
                                <span class="paragraph bold  col-4 no-padding-sides text-right fw6" id="grand-total-amount-detail">{{ $cart->base_grand_total }}</span> {{--if cuarrency is applied replace with this $cart->base_grand_total)--}}
                                <span class="s-paragraph col-8 no-padding-sides">Tax calculated at checkout</span>
                            </div>
                            <div class="row">
                                <div class="col-12 no-padding-sides">
                                    <coupon-component></coupon-component>
                                </div>

                            </div>
                            <div class="row" id="proceed-to-checkout-container">
                                <div class="col-12 no-padding-sides">
                                    <a id="proceed-to-checkout" style="" href="{{ route('shop.checkout.onepage.index') }}" class="theme-btn text-capatilize col-12 remove-decoration fw6 text-center paragraph white bold">
                                        {{ __('velocity::app.checkout.proceed') }}
                                    </a>
                                </div>

                            </div>
                        </div>


                    </div>
                @else
                    <div class="fs16 col-12 empty-cart-message">
                        {{ __('shop::app.checkout.cart.empty') }}
                    </div>

                    <a
                            class="fs16 mt15 col-12 remove-decoration continue-shopping paragraph bold"
                            href="{{ route('shop.home.index') }}">

                        <button type="button"  class="theme-btn remove-decoration">
                            {{ __('shop::app.checkout.cart.continue-shopping') }}
                        </button>
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

            </section>
        </div>
    </script>

    <script type="text/javascript" id="cart-template">
        (() => {
            Vue.component('cart-component', {
                template: '#cart-template',
                data: function () {
                    return {
                        isMobileDevice: this.isMobile(),
                    }
                }
            })

            function removeLink(message) {
                if (!confirm(message))
                event.preventDefault();
            }
        })()
    </script>
@endpush