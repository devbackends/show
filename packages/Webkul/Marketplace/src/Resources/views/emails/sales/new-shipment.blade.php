@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="https://www.2agunshow.com/images/logo.png">
        </a>
    </div>
    <?php $sellerOrder = $sellerShipment->order; ?>


    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('marketplace::app.mail.sales.shipment.heading') }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.dear', ['customer_name' => $sellerOrder->seller->customer->name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('marketplace::app.mail.sales.shipment.greeting', [
                    'order_id' => '<a href="' . route('customer.orders.view', $sellerOrder->order_id) . '" style="color: #0041FF; font-weight: bold;">#' . $sellerOrder->order_id . '</a>',
                    'created_at' => $sellerOrder->created_at
                    ])
                !!}
            </p>
        </div>

        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ __('shop::app.mail.shipment.summary') }}
        </div>

        <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
            <div style="line-height: 25px;">
                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.shipping-address') }}
                </div>

                <div>
                    {{ $sellerOrder->order->shipping_address->name }}
                </div>

                <div>
                    {{ $sellerOrder->order->shipping_address->address1 }} {{ $sellerOrder->order->shipping_address->address2 ? '</br>' . $sellerOrder->order->shipping_address->address2 : '' }}
                </div>
                <div>
                  {{ $sellerOrder->order->shipping_address->city }}, {{ $sellerOrder->order->shipping_address->state }} {{ $sellerOrder->order->shipping_address->postcode }}
                </div>
                <div>
                    {{ core()->country_name($sellerOrder->order->shipping_address->country) }}
                </div>

                <div>---</div>

                <div style="margin-bottom: 40px;">
                    {{ __('shop::app.mail.order.contact') }} : {{ $sellerOrder->order->shipping_address->phone }}
                </div>

                <div style="font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.shipping') }}
                </div>

                <div style="font-size: 16px;color: #242424;">
                    <div style="font-weight: bold;">
                        {{ $sellerOrder->order->shipping_title }}
                    </div>

                    <div style="margin-top: 5px;">
                        <span style="font-weight: bold;">{{ __('shop::app.mail.shipment.carrier') }} : </span>{{ $sellerShipment->shipment->carrier_title }}
                    </div>

                    <div style="margin-top: 5px;">
                        <span style="font-weight: bold;">{{ __('shop::app.mail.shipment.tracking-number') }} : </span>{{ $sellerShipment->shipment->track_number }}
                    </div>
                </div>
            </div>

            <div style="line-height: 25px;">
                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.billing-address') }}
                </div>

                <div>
                    {{ $sellerOrder->order->billing_address->name }}
                </div>

                <div>
                    {{ $sellerOrder->order->billing_address->address1 }}  {{ $sellerOrder->order->billing_address->address2 ? '</br>' . $sellerOrder->order->billing_address->address2 : '' }}
                </div>
                <div>
                  {{ $sellerOrder->order->billing_address->city }}, {{ $sellerOrder->order->billing_address->state }} {{ $sellerOrder->order->billing_address->postcode }}
                </div>
                <div>
                    {{ core()->country_name($sellerOrder->order->billing_address->country) }}
                </div>

                <div>---</div>

                <div style="margin-bottom: 40px;">
                    {{ __('shop::app.mail.order.contact') }} : {{ $sellerOrder->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px; color: #242424;">
                    {{ __('shop::app.mail.order.payment') }}
                </div>

                <div style="font-weight: bold;font-size: 16px; color: #242424;">
                    {{ core()->getConfigData('sales.paymentmethods.' . $sellerOrder->order->payment->method . '.title') }}
                </div>
            </div>
        </div>

        <div style="width: 100%;overflow-x: auto;">
            <table style="width: 100%;border-collapse: collapse;text-align: left;">
                <thead>
                    <tr>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.qty') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($sellerShipment->items as $sellerShipmentItem)

                        <tr>
                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ $sellerShipmentItem->item->sku }}</td>
                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ $sellerShipmentItem->item->name }}</td>
                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ $sellerShipmentItem->item->qty }}</td>
                        </tr>

                    @endforeach

                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block;width: 100%">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.help', [
                        'seller_name' => '<a style="color:#0041FF" href="' . $contact_link . '">' . $seller->shop_title . '</a>'
                        ])
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.thanks') }}
            </p>
        </div>
    </div>
@endcomponent
