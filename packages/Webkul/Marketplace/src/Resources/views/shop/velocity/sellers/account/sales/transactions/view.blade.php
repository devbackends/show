@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.transactions.view-title', ['transaction_id' => $transaction->transaction_id]) }}
@endsection

@push('css')
    <style>
        .profile-page-content td{
            border-top: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@section('content')

    <div class="account-layout right m10">

        <div class="account-head">
            <span class="account-heading">
                {{ __('marketplace::app.shop.sellers.account.sales.transactions.view-title', ['transaction_id' => $transaction->transaction_id]) }}
            </span>

            <div class="account-action">
            </div>
            <span></span>
        </div>

        {!! view_render_event('marketplace.sellers.account.sales.transactions.view.before', ['transaction' => $transaction]) !!}

        <div  class="sale-container">

            <div class="sale-section">
                <div class="account-table-content profile-page-content">
                    <div class="table">
                        <table>
                            <tbody>
                                <tr>
                                    <th>
                                        {{ __('marketplace::app.shop.sellers.account.sales.transactions.created-at') }}
                                    </th>

                                    <td>
                                        {{ core()->formatDate($transaction->created_at, 'd M Y') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ __   ('marketplace::app.shop.sellers.account.sales.transactions.payment-method') }}
                                    </th>

                                    <td>
                                        {{ $transaction->method }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ __('marketplace::app.shop.sellers.account.sales.transactions.total') }}
                                    </th>

                                    <td>
                                        {{ core()->formatBasePrice($transaction->base_total) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ __('marketplace::app.shop.sellers.account.sales.transactions.comment') }}
                                    </th>

                                    <td>
                                        {{ $transaction->comment }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
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

                    <div style="float: right;" class="totals">
                        <table class="table sale-summary">
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