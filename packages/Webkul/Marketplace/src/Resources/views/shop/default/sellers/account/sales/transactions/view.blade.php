@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.transactions.view-title', ['transaction_id' => $transaction->transaction_id]) }}
@endsection

@section('content')

    <div class="account-layout">

        <div class="account-head">
            <h1 class="h3">{{ __('marketplace::app.shop.sellers.account.sales.transactions.view-title', ['transaction_id' => $transaction->transaction_id]) }}</h1>
            <div class="account-action">
            </div>
            <span></span>
        </div>

        {!! view_render_event('marketplace.sellers.account.sales.transactions.view.before', ['transaction' => $transaction]) !!}

        <div class="sale-container">

            <div class="sale-section">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>{{ __('marketplace::app.shop.sellers.account.sales.transactions.created-at') }}</label>
                            <p>{{ core()->formatDate($transaction->created_at, 'd M Y') }}</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>{{ __('marketplace::app.shop.sellers.account.sales.transactions.payment-method') }}</label>
                            <p>{{ $transaction->method }}</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>{{ __('marketplace::app.shop.sellers.account.sales.transactions.total') }}</label>
                            <p>{{ core()->formatBasePrice($transaction->base_total) }}</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>{{ __('marketplace::app.shop.sellers.account.sales.transactions.comment') }}</label>
                            <p>{{ $transaction->comment }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php $sellerOrder = $transaction->order; ?>

            <div class="sale-section">
                <div class="secton-title">
                    <span>{{ __('marketplace::app.shop.sellers.account.sales.transactions.order-id', ['order_id' => $sellerOrder->order_id]) }}</span>
                </div>

                <div class="section-content">

                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.total') }}</th>
                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.transactions.commission') }}</th>
                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.transactions.seller-total') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($sellerOrder->items as $sellerOrderItem)

                                    <tr>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">
                                            {{ $sellerOrderItem->item->name }}

                                            @if (isset($sellerOrderItem->additional['attributes']))
                                                <div class="item-options">

                                                    @foreach ($sellerOrderItem->additional['attributes'] as $attribute)
                                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                    @endforeach

                                                </div>
                                            @endif
                                        </td>

                                        <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">{{ core()->formatPrice($sellerOrderItem->item->price, $sellerOrder->order->order_currency_code) }}</td>

                                        <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}">{{ $sellerOrderItem->item->qty_ordered }}</td>

                                        <td data-value="{{ __('shop::app.customer.account.order.view.total') }}">{{ core()->formatPrice($sellerOrderItem->item->total, $sellerOrder->order->order_currency_code) }}</td>

                                        <td data-value="{{ __('marketplace::app.shop.sellers.account.sales.transactions.commission') }}">{{ core()->formatPrice($sellerOrderItem->commission, $sellerOrder->order->order_currency_code) }}</td>

                                        <td data-value="{{ __('marketplace::app.shop.sellers.account.sales.transactions.seller-total') }}">{{ core()->formatPrice($sellerOrderItem->seller_total, $sellerOrder->order->order_currency_code) }}</td>
                                    </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="totals">
                        <table class="sale-summary">
                            <tbody>
                                <tr>
                                    <td>{{ __('marketplace::app.shop.sellers.account.sales.transactions.sub-total') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($sellerOrder->sub_total, $sellerOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($sellerOrder->base_shipping_amount, $sellerOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('marketplace::app.shop.sellers.account.sales.transactions.tax') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($sellerOrder->tax_amount, $sellerOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr class="bold">
                                    <td>{{ __('marketplace::app.shop.sellers.account.sales.transactions.commission') }}</td>
                                    <td>-</td>
                                    <td>-{{ core()->formatPrice($sellerOrder->commission, $sellerOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr class="bold">
                                    <td>{{ __('marketplace::app.shop.sellers.account.sales.transactions.seller-total') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($sellerOrder->seller_total, $sellerOrder->order->order_currency_code) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

        {!! view_render_event('marketplace.sellers.account.sales.transactions.view.after', ['transaction' => $transaction]) !!}

    </div>

@endsection