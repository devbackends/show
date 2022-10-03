@component('shop::emails.layouts.master')

    <div style="margin-bottom: 32px; text-align: center;">
        <a href="{{ config('app.url') }}" style="display: inline-block;">
            @if (core()->getConfigData('general.design.admin_logo.logo_image'))
            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image')) }}" alt="{{ config('app.name') }}" style="width: 170px;" />
            @else
            {{--<img src="{{ asset('images/store-logo.png') }}" alt="{{ config('app.name') }}"/>--}}
            <span class="icon gun-logo-icon"></span>
            @endif
        </a>
    </div>
    <div style=" text-align: center;">
        <p style="font-weight: 700; color: #111111;">
            {{ __('shop::app.mail.order.heading') }}
        </p>

        <p style="color: #111111;">
            {{ __('shop::app.mail.order.dear-admin', ['admin_name' => config('mail.from.name')]) }},
        </p>

        <p style="color: #111111;">
            {!! __('shop::app.mail.order.greeting-admin', [
            'order_id' => '<a href="' . route('customer.orders.view', $order->id) . '" style="color: #0C91A6; font-weight: 700; text-decoration: none;">#' . $order->increment_id . '</a>',
            'created_at' => $order->created_at
            ]) !!}
        </p>
    </div>
    <div
        style="margin-top: 40px; border-top: 1px solid #ccc; padding-top: 40px;">
        <p style="font-weight: 700; font-size: 24px; margin-bottom: 32px; color: #111111;">{{ __('shop::app.mail.order.summary') }}</p>
    </div>
    <div style="margin-bottom: 24px;">
        <?php
        $shipping_address = $order->shipping_address;
        $fflAddress = $order->fflAddress;
        $finalAddress = [
            'name' => $shipping_address? $shipping_address->name: $fflAddress->company_name,
            'address1' => $shipping_address? $shipping_address->address1: $fflAddress->address1,
            'city' => $shipping_address? $shipping_address->city: $fflAddress->city,
            'state' => $shipping_address? $shipping_address->state: $fflAddress->state,
            'postcode' => $shipping_address? $shipping_address->postcode: $fflAddress->postcode,
            'country' => $shipping_address? $shipping_address->country: $fflAddress->country,
            'phone' => $shipping_address? $shipping_address->phone: $fflAddress->phone,
        ];
        ?>
        <div class="two-cols two-cols-one" style="display: inline-block; vertical-align: top;">
            <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.shipping-address') }}</p>
            <p style="color: #111111;">
                @if($finalAddress['name'] && !empty($finalAddress['name']))
                    {{ $finalAddress['name'] }}, <br>
                @endif
                {{ $finalAddress['address1'] }}<br />{{ $finalAddress['city'] }}, {{ $finalAddress['state'] }} {{ $finalAddress['postcode'] }} <br>{{ core()->country_name($finalAddress['country']) }} <br>{{ __('shop::app.mail.order.contact') }} : {{ $finalAddress['phone'] }}</p>
        </div>
        <div class="two-cols two-cols-two" style="display: inline-block; vertical-align: top;">
            <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.billing-address') }}</p>
            <p style="color: #111111;">{{ $order->billing_address->name }}, <br>{{ $order->billing_address->address1 }}<br />{{ $order->billing_address->city }}, {{ $order->billing_address->state }} {{ $order->billing_address->postcode }} <br>{{ core()->country_name($order->billing_address->country) }} <br> --- <br>{{ __('shop::app.mail.order.contact') }} : {{ $order->billing_address->phone }}</p>
        </div>
    </div>
    <div style="margin-bottom: 24px;">
        <div class="two-cols two-cols-one" style="display: inline-block; vertical-align: top;">
            <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.shipping') }}</p>
            <p style="color: #111111;">{{ $order->shipping_title }}</p>
        </div>
        <div class="two-cols two-cols-two" style="display: inline-block; vertical-align: top;">
            <p style="font-weight: 700; color: #111111;">{{ __('shop::app.mail.order.payment') }}</p>
            <p style="color: #111111;">{{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
        </div>
    </div>
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
                <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}" style="text-align: left;padding: 8px; color: #111111; word-wrap: break-word;">
                    {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                </td>
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
                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}" style="text-align: left;padding: 8px; color: #111111;">
                    {{ $item->qty_ordered }}
                </td>
                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}" style="text-align: left;padding: 8px; color: #111111; text-align: right;">
                    {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="order-summary" style="margin: 0 8px 40px auto;">
        <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
            <span>{{ __('shop::app.mail.order.subtotal') }}</span>
            <span style="float: right;">
                {{ core()->formatBasePrice($order->base_sub_total) }}
            </span>
        </p>

        <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
            <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
            <span style="float: right;">
                {{ core()->formatBasePrice($order->base_shipping_amount) }}
            </span>
        </p>

        @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($order, true) as $taxRate => $baseTaxAmount )
        <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
            <span id="taxrate-{{ core()->taxRateAsIdentifier($taxRate) }}">{{ __('shop::app.mail.order.tax') }} {{ $taxRate }} %</span>
            <span id="basetaxamount-{{ core()->taxRateAsIdentifier($taxRate) }}" style="float: right;">
                {{ core()->formatBasePrice($baseTaxAmount) }}
            </span>
        </p>
        @endforeach

        @if ($order->discount_amount > 0)
        <p style="margin-bottom: 0; margin-top: 0; color: #111111;">
            <span>{{ __('shop::app.mail.order.discount') }}</span>
            <span style="float: right;">
                {{ core()->formatBasePrice($order->base_discount_amount) }}
            </span>
        </p>
        @endif

        <p style="margin-bottom: 0; margin-top: 0; color: #111111; font-weight: 700;">
            <span>{{ __('shop::app.mail.order.grand-total') }}</span>
            <span style="float: right;">
                {{ core()->formatBasePrice($order->base_grand_total) }}
            </span>
        </p>
    </div>
    @if(isset($seller->shop_title) && isset($contact_link))
    <div style="text-align: center;">
        <p style="color: #111111;">{!!
                __('shop::app.mail.order.help', [
                    'seller_name' => '<a style="color:#0C91A6; font-weight: 700;" href="' . $contact_link . '">' . $seller->shop_title . '</a>'
                ])
                !!}</p>
        <p style="color: #111111;">
            {{ __('shop::app.mail.order.thanks') }}
        </p>
    </div>
    @endif
@endcomponent