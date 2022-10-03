@extends('shop::layouts.master')

@section('page_title')
{{ __('shop::app.checkout.success.title') }}
@stop

@section('content-wrapper')

<!-- <div class="order-success-content" style="min-height: 300px;">
        <h1>{{ __('shop::app.checkout.success.thanks') }}</h1>

        <p>{{ __('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id]) }}</p>

        <p>{{ __('shop::app.checkout.success.info') }}</p>

        {{ view_render_event('bagisto.shop.checkout.continue-shopping.before', ['order' => $order]) }}

        <div class="misc-controls">
            <a style="display: inline-block" href="{{ route('shop.home.index') }}" class="btn btn-primary">
                {{ __('shop::app.checkout.cart.continue-shopping') }}
            </a>
        </div>

        {{ view_render_event('bagisto.shop.checkout.continue-shopping.after', ['order' => $order]) }}

    </div> -->

<section class="order-confirmation">
    <div class="container">
        <div class="order-confirmation__head">

            <h1 class="h2">{{ __('shop::app.checkout.success.thanks') }}</h1>

            <div class="h3 mb-4">
                <p>{{ __('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id]) }}</p>
                <p>{{ __('shop::app.checkout.success.info') }}</p>
            </div>
              @php $counter=0; @endphp
            @foreach($order->items as $item)
                @if ($bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $item->product->product_id))
                    @if($counter==0)
                        @if($bookingProduct->booking_confirmation_message)
                        <!-- Booking confirmation message -->
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-8">
                                    <div class="order-confirmation__booking-info">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <i class="far fa-info-circle"></i>
                                            </div>
                                            <div class="col">
                                                <p class="font-weight-bold">Booking confirmation info</p>
                                                <p class="text-left mb-0">{{$bookingProduct->booking_confirmation_message}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Booking confirmation message -->
                      @endif
                    @endif
                    @php $counter+=1; @endphp
                @endif
            @endforeach

            {{ view_render_event('bagisto.shop.checkout.continue-shopping.before', ['order' => $order]) }}

            @if(!$isCartEmpty)
                <div class="order-confirmation__head-actions mb-3">
                    <p class="d-inline-block align-middle mb-0">{{ __('shop::app.checkout.success.other-products') }}</p><a href="{{route('marketplace.cart.view')}}" class="btn btn-primary ml-3">{{ __('shop::app.checkout.success.continue-checkout') }}</a>
                    <div class="mt-3">
                        <p>{{ __('shop::app.checkout.success.or') }}</p>
                    </div>
                </div>
            @endif

            <div>
                <a href="{{ route('shop.home.index') }}" class="btn btn-outline-primary">
                {{ __('shop::app.checkout.success.continue-shopping') }}
                </a>
            </div>

            {{ view_render_event('bagisto.shop.checkout.continue-shopping.after', ['order' => $order]) }}

        </div>

        <div class="row">
            <div class="col-12">
                <div class="order-confirmation__content clearfix">
                    <h3>Order Details</h3>
                    <div class="row justify-content-lg-between">
                        <div class="col-12 col-md-4 col-xl-3">
                            <label class="font-weight-bold">Customer</label>
                            <p>{{$order->getCustomerFullNameAttribute()}} {{$order->customer->email}}</p>
                        </div>
                        @if($order->shippingAddress)
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Shipping address</label>
                                    <p>@include ('admin::sales.address', ['address' => $order->shippingAddress])</p>
                                </div>
                            </div>
                        @endif
                        @if($order->ffl_address)
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>FFL Address</label>
                                    <p>@include ('admin::sales.address', ['address' => $order->ffl_address])</p>

                                </div>
                            </div>
                        @endif
                        <div class="col-12 col-md-4 col-xl-3">
                            <label class="font-weight-bold">Payment method</label>
                            @if(isset(\Illuminate\Support\Facades\Config::get('paymentmethods')[$order->payment->method]['title']))
                            <p>{{\Illuminate\Support\Facades\Config::get('paymentmethods')[$order->payment->method]['title']}}</p>
                            @else
                                <p>{{$order->payment->method}}</p>
                            @endif
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>QTY</th>
                                <th>Product</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="font-weight-bold">{{$order->id}}</td>
                                    <td class="font-weight-bold text-center">{{$item->qty_ordered}}</td>
                                    <td>
                                        @if($item->product->getBaseImageUrlAttribute())
                                        <img src="{{$item->product->getBaseImageUrlAttribute()}}" alt="Product image">
                                        @else
                                        <img src="/vendor/webkul/ui/assets/images/product/small-product-placeholder.png" alt="Product image">
                                        @endif
                                        <div>
                                            <p>{{$item->name}}<br> UPC: {{$item->sku}}</p>
                             {{--               @if($item->type === 'booking')
                                                @if(isset($item->additional['booking']) || isset($item->additional['rentalType']))
                                                    <p>{{$item->additional['attributes'][0]['option_label']}}<br>
                                                        {{$item->additional['attributes'][1]['option_label']}} - {{$item->additional['attributes'][2]['option_label']}}</p>
                                                @else
                                                    <p>{{$item->additional['attributes'][0]['option_label']}} - {{$item->additional['attributes'][1]['option_label']}}</p>
                                                @endif
                                            @endif--}}
                                        </div>
                                    </td>

                                    <td class="text-right font-weight-bold">{{core()->formatBasePrice($item->base_price)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="order-confirmation__total">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span>Subtotal</span>
                                <span>{{core()->formatBasePrice($order->sub_total)}}</span>
                            </li>
                            <li class="list-group-item">
                                <span>Shipping</span>
                                <span>{{core()->formatBasePrice($order->base_shipping_amount)}}</span>
                            </li>
                            @if($order->coupon_code)
                                <li class="list-group-item">
                                    <span>Coupon Code</span>
                                    <span>{{ $order->coupon_code }}</span>
                                </li>
                            @endif
                            @if($order->base_discount_amount > 0)
                            <li class="list-group-item">
                                <span>Discount Amount</span>
                                <span>{{core()->formatBasePrice($order->base_discount_amount)}}</span>
                            </li>
                            @endif
                            <li class="list-group-item">
                                <span>Tax</span>
                                <span>{{core()->formatBasePrice($order->base_tax_amount)}}</span>
                            </li>
                            <li class="list-group-item">
                                <span>TOTAL</span>
                                <span>{{core()->formatBasePrice($order->base_grand_total)}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection