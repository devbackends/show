<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="Cache-control" content="no-cache">

        <style type="text/css">
            body, th, td, h5 {
                font-size: 12px;
                color: #000;
            }

            .container {
                padding: 20px;
                display: block;
            }

            .invoice-summary {
                margin-bottom: 20px;
            }

            .table {
                margin-top: 20px;
            }

            .table table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            .table thead th {
                font-weight: 700;
                border-top: solid 1px #d3d3d3;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                padding: 5px 10px;
                background: #F4F4F4;
            }

            .table thead th:last-child {
                border-right: solid 1px #d3d3d3;
            }

            .table tbody td {
                padding: 5px 10px;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                color: $font-color;
                vertical-align: middle;
            }

            .table tbody td p {
                margin: 0;
            }

            .table tbody td:last-child {
                border-right: solid 1px #d3d3d3;
            }

           .sale-summary {
                margin-top: 40px;
                float: right;
            }

            .sale-summary tr td {
                padding: 3px 5px;
            }

            .sale-summary tr.bold {
                font-weight: 600;
            }

            .label {
                color: #000;
                font-weight: 600;
            }

        </style>
    </head>

    <body style="background-image: none;background-color: #fff;">
        <div class="container">

            <div class="invoice-summary">

                <div class="row">
                    <span class="label">{{ __('shop::app.customer.account.order.view.invoice-id') }} -</span>
                    <span class="value">#{{ $sellerInvoice->invoice_id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('shop::app.customer.account.order.view.order-id') }} -</span>
                    <span class="value">#{{ $sellerInvoice->invoice->order_id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('shop::app.customer.account.order.view.order-date') }} -</span>
                    <span class="value">{{ core()->formatDate($sellerInvoice->order->created_at, 'M d, Y') }}</span>
                </div>

                <div class="table address">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%">{{ __('shop::app.customer.account.order.view.bill-to') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.ship-to') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <p>{{ $sellerInvoice->invoice->order->billing_address->name }}</p>
                                    <p>{{ $sellerInvoice->invoice->order->billing_address->address1 }}, {{ $sellerInvoice->invoice->order->billing_address->address2 ? $sellerInvoice->invoice->order->billing_address->address2 . ',' : '' }}</p>
                                    <p>{{ $sellerInvoice->invoice->order->billing_address->city }}</p>
                                    <p>{{ $sellerInvoice->invoice->order->billing_address->state }}</p>
                                    <p>{{ core()->country_name($sellerInvoice->invoice->order->billing_address->country) }} {{ $sellerInvoice->invoice->order->billing_address->postcode }}</p>
                                    {{ __('shop::app.customer.account.order.view.contact') }} : {{ $sellerInvoice->invoice->order->billing_address->phone }}
                                </td>
                                <td>
                                    <p>{{ $sellerInvoice->invoice->order->shipping_address->name }}</p>
                                    <p>{{ $sellerInvoice->invoice->order->shipping_address->address1 }}, {{ $sellerInvoice->invoice->order->shipping_address->address2 ? $sellerInvoice->invoice->order->shipping_address->address2 . ',' : '' }}</p>
                                    <p>{{ $sellerInvoice->invoice->order->shipping_address->city }}</p>
                                    <p>{{ $sellerInvoice->invoice->order->shipping_address->state }}</p>
                                    <p>{{ core()->country_name($sellerInvoice->invoice->order->shipping_address->country) }} {{ $sellerInvoice->invoice->order->shipping_address->postcode }}</p>
                                    {{ __('shop::app.customer.account.order.view.contact') }} : {{ $sellerInvoice->invoice->order->shipping_address->phone }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table payment-shipment">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%">{{ __('shop::app.customer.account.order.view.payment-method') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.shipping-method') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    {{ core()->getConfigData('sales.paymentmethods.' . $sellerInvoice->invoice->order->payment->method . '.title') }}
                                </td>
                                <td>
                                    {{ $sellerInvoice->invoice->order->shipping_title }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table items">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.discount') }}</th>
                                <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($sellerInvoice->items as $sellerInvoiceItem)
                                <?php $baseInvoiceItem = $sellerInvoiceItem->item; ?>
                                <tr>
                                    <td>{{ $baseInvoiceItem->name }}</td>

                                    <td>
                                        {{ core()->formatPrice($baseInvoiceItem->price, $sellerInvoice->invoice->order->order_currency_code) }}
                                    </td>

                                    <td>{{ $baseInvoiceItem->qty }}</td>

                                    <td>
                                        {{ core()->formatPrice($baseInvoiceItem->total, $sellerInvoice->invoice->order->order_currency_code) }}
                                    </td>

                                    <td>
                                        {{ core()->formatPrice($baseInvoiceItem->tax_amount, $sellerInvoice->invoice->order->order_currency_code) }}
                                    </td>

                                    <td>
                                        {{ core()->formatPrice($baseInvoiceItem->discount_amount, $sellerInvoice->invoice->order->order_currency_code) }}
                                    </td>

                                    <td>
                                        {{ core()->formatPrice($baseInvoiceItem->total + $baseInvoiceItem->tax_amount - $sellerInvoice->discount_amount, $sellerInvoice->invoice->order->order_currency_code) }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>


                <table class="sale-summary">
                    <tr>
                        <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($sellerInvoice->sub_total, $sellerInvoice->invoice->order->order_currency_code) }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($sellerInvoice->shipping_amount, $sellerInvoice->invoice->order->order_currency_code) }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($sellerInvoice->tax_amount, $sellerInvoice->invoice->order->order_currency_code) }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.order.view.discount') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($sellerInvoice->discount_amount, $sellerInvoice->invoice->order->order_currency_code) }}</td>
                    </tr>

                    <tr class="bold">
                        <td>{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($sellerInvoice->grand_total, $sellerInvoice->invoice->order->order_currency_code) }}</td>
                    </tr>
                </table>

            </div>

        </div>
    </body>
</html>