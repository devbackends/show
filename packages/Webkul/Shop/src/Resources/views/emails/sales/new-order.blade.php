@component('shop::emails.layouts.master')

<div style="margin-bottom: 32px; text-align: center;">
    <a href="https://www.2agunshow.com/" style="display: inline-block;">
        @include ('shop::emails.layouts.logo')
    </a>
</div>
<div style=" text-align: center;">
    <p style="font-weight: 700; color: #111111;">
        {{ __('shop::app.mail.order.heading') }}
    </p>
    <p style="color: #111111;">
    {{ __('shop::app.mail.order.dear', ['customer_name' => $order->customer_full_name]) }},
    </p>
    <p style="color: #111111;">
    {!! __('shop::app.mail.order.greeting', [
            'order_id' => '<a href="' . route('customer.orders.view', $order->id) . '" style="color: #0C91A6; font-weight: 700;">#' . $order->increment_id . '</a>',
            'created_at' => $order->created_at
            ])
            !!}
</div>
<div style="margin-top: 40px; border-top: 1px solid #ccc; padding-top: 40px;">
    <p style="font-weight: 700; font-size: 24px; margin-bottom: 32px; color: #111111;">{{ __('shop::app.mail.order.summary') }}</p>
</div>
<div style="margin-bottom: 24px;">
    @if ($order->shipping_address)
    <div class="two-cols two-cols-one" style="display: inline-block; vertical-align: top;">
        <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.shipping-address') }}</p>
        <p style="color: #111111;">{{ $order->shipping_address->name }} <br>{{ $order->shipping_address->address1 }}<br />{{ $order->shipping_address->city }}, {{ $order->shipping_address->state }} {{ $order->shipping_address->postcode }}<br>{{ core()->country_name($order->shipping_address->country) }}<br> --- <br> {{ __('shop::app.mail.order.contact') }}: {{ $order->shipping_address->phone }}</p>
    </div>
    @endif
    <div class="two-cols two-cols-two" style="display: inline-block; vertical-align: top;">
        <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.billing-address') }}</p>
        <p style="color: #111111;">{{ $order->billing_address->name }} <br>{{ $order->billing_address->address1 }}<br />{{ $order->billing_address->city }}, {{ $order->billing_address->state }} {{ $order->billing_address->postcode }}<br>{{ core()->country_name($order->billing_address->country) }}<br> --- <br> {{ __('shop::app.mail.order.contact') }}: {{ $order->billing_address->phone }}</p>
    </div>
</div>
<div style="margin-bottom: 24px;">
    @if ($order->shipping_address)
    <div class="two-cols two-cols-one" style="display: inline-block; vertical-align: top;">
        <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.shipping') }}</p>
        <p style="color: #111111;">{{ $order->shipping_title }}</p>
    </div>
    @endif
    <div class="two-cols two-cols-two" style="display: inline-block; vertical-align: top;">
        <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.payment') }}</p>
        <p style="color: #111111;">{{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
    </div>
</div>
@php $counter=0; @endphp
@foreach($order->items as $item)
    @if ($bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $item->product->product_id))
        @if($counter==0)
            @if($bookingProduct->booking_confirmation_message)
            <!-- Booking confirmation message -->
            <div style="margin-bottom: 24px; padding: 24px; background: #f4f4f4; border-radius: 8px;">
                <p class="margin-bottom: 8px; font-weight: bold;">Booking confirmation info</p>
                <p style="margin-bottom: 0;">{{$bookingProduct->booking_confirmation_message}}</p>
            </div>
            <!-- END Booking confirmation message -->
            @endif
        @endif
        @php $counter+=1; @endphp
    @endif
@endforeach
<table class="mail-table" style="overflow-x: auto; border-collapse: collapse; border-spacing: 0; width: 100%; border-bottom: 1px solid #cccccc; margin-bottom: 20px; table-layout: fixed;">
    <thead>
        <tr style="background-color: #f2f2f2">
            <th style="text-align: left;padding: 8px; color: #111111; min-width: 35px;">{{ __('shop::app.customer.account.order.view.SKU') }}</th>
            <th style="text-align: left;padding: 8px; color: #111111;" class="table-cell-name">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
            <th style="text-align: left;padding: 8px; color: #111111; width: 30px;">{{ __('shop::app.customer.account.order.view.qty') }}</th>
            <th style="text-align: left;padding: 8px; color: #111111; text-align: right;">{{ __('shop::app.customer.account.order.view.price') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($order->items as $item)
        <tr>
            <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}" style="text-align: left;padding: 8px; color: #111111; word-wrap: break-word;">{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>

            <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}" style="text-align: left;padding: 8px; color: #111111;">
                {{ $item->name }}

                @if($item->type === 'booking')
                    @if(isset($item->additional['booking']) || isset($item->additional['rentalType']))
                        <p>{{$item->additional['attributes'][0]['option_label']}}<br>
                            {{$item->additional['attributes'][1]['option_label']}} - {{$item->additional['attributes'][2]['option_label']}}</p>
                    @else
                        <p>{{$item->additional['attributes'][0]['option_label']}} - {{$item->additional['attributes'][1]['option_label']}}</p>
                    @endif
                @elseif (isset($item->additional['attributes']))
                    <div class="item-options">

                        @foreach ($item->additional['attributes'] as $attribute)
                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                        @endforeach

                    </div>
                @endif
            </td>

            <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}" style="text-align: left;padding: 8px; color: #111111;">{{ $item->qty_ordered }}</td>

            <td data-value="{{ __('shop::app.customer.account.order.view.price') }}" style="text-align: left;padding: 8px; color: #111111; text-align: right;">{{ core()->formatPrice($item->price, $order->order_currency_code) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="order-summary" style="margin: 0 8px 40px auto;">
    <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
        <span>{{ __('shop::app.mail.order.subtotal') }}</span>
        <span style="float: right;">
            {{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}
        </span>
    </p>

    @if ($order->shipping_address)
    <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
        <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
        <span style="float: right;">
            {{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}
        </span>
    </p>
    @endif

    @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($order, false) as $taxRate => $taxAmount )
    <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
        <span>{{ __('shop::app.mail.order.tax') }} {{ $taxRate }} %</span>
        <span id="taxamount" style="float: right;">
            {{ core()->formatPrice($taxAmount, $order->order_currency_code) }}
        </span>
    </p>
    @endforeach

    @if ($order->discount_amount > 0)
    <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
        <span>{{ __('shop::app.mail.order.discount') }}</span>
        <span style="float: right;">
            {{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}
        </span>
    </p>
    @endif

    <p style="margin-bottom: 0; margin-top: 0; font-weight: 700; color: #111111;">
        <span>{{ __('shop::app.mail.order.grand-total') }}</span>
        <span style="float: right;">
            {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
        </span>
    </p>
</div>

@if(isset($contact_link) && isset($seller->shop_title))
<div style="text-align: center;">
    <p style="color: #111111;">{{ __('shop::app.mail.order.final-summary') }}</p>
    <p style="color: #111111;">
        {!!
            __('shop::app.mail.order.help', [
                'seller_name' => '<a style="color:#0C91A6; font-weight: 700;" href="' . $contact_link . '">' . $seller->shop_title . '</a>'
            ])
        !!}
    </p>

    <p style="color: #111111;">
        {{ __('shop::app.mail.order.thanks') }}
    </p>
</div>
@endif
@endcomponent