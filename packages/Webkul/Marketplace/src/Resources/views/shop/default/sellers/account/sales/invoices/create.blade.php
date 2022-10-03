@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.invoices.create-title') }}
@endsection

@section('content')

    <div class="account-layout">

        <form method="POST" action="{{ route('marketplace.account.invoices.store', $sellerOrder->order_id) }}" @submit.prevent="onSubmit">
            @csrf

            <div class="account-head">
                <span class="account-heading">
                    {{ __('marketplace::app.shop.sellers.account.sales.invoices.create-title') }}
                </span>

                <div class="account-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('marketplace::app.shop.sellers.account.sales.invoices.create') }}
                    </button>
                </div>
                <span></span>
            </div>

            {!! view_render_event('marketplace.sellers.account.sales.invoices.create.before', ['sellerOrder' => $sellerOrder]) !!}

            <div class="sale-container">

                <div class="sale-section">
                    <div class="section-content">
                        <div class="row">
                            <span class="title">
                                {{ __('marketplace::app.shop.sellers.account.sales.invoices.order-id') }}
                            </span>

                            <span class="value">
                                <a href="{{ route('marketplace.account.orders.view', $sellerOrder->order_id) }}">#{{ $sellerOrder->order_id }}</a>
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('marketplace::app.shop.sellers.account.sales.orders.placed-on') }}
                            </span>

                            <span class="value">
                                {{ core()->formatDate($sellerOrder->created_at, 'd M Y') }}
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('marketplace::app.shop.sellers.account.sales.orders.status') }}
                            </span>

                            <span class="value">
                                {{ $sellerOrder->status_label }}
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('marketplace::app.shop.sellers.account.sales.orders.customer-name') }}
                            </span>

                            <span class="value">
                                {{ $sellerOrder->order->customer_full_name }}
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('marketplace::app.shop.sellers.account.sales.orders.email') }}
                            </span>

                            <span class="value">
                                {{ $sellerOrder->order->customer_email }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="sale-section">
                    <div class="secton-title">
                        <span>{{ __('shop::app.customer.account.order.view.products-ordered') }}</span>
                    </div>

                    <div class="section-content">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('marketplace::app.shop.sellers.account.sales.invoices.product-name') }}</th>
                                        <th>{{ __('marketplace::app.shop.sellers.account.sales.invoices.qty-ordered') }}</th>
                                        <th>{{ __('marketplace::app.shop.sellers.account.sales.invoices.qty-to-invoice') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($sellerOrder->items as $sellerOrderItem)
                                        <tr>
                                            <td>
                                                {{ $sellerOrderItem->item->name }}

                                                @if (isset($sellerOrderItem->additional['attributes']))
                                                    <div class="item-options">

                                                        @foreach ($sellerOrderItem->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach

                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $sellerOrderItem->item->qty_ordered }}</td>
                                            <td>
                                                <div class="control-group" :class="[errors.has('invoice[items][{{ $sellerOrderItem->order_item_id }}]') ? 'has-error' : '']">
                                                    <input type="text" v-validate="'required|numeric|min:0'" class="control" id="invoice[items][{{ $sellerOrderItem->order_item_id }}]" name="invoice[items][{{ $sellerOrderItem->order_item_id }}]" value="{{ $sellerOrderItem->qty_to_invoice }}" data-vv-as="&quot;{{ __('admin::app.sales.invoices.qty-to-invoice') }}&quot;"/>

                                                    <span class="control-error" v-if="errors.has('invoice[items][{{ $sellerOrderItem->order_item_id }}]')">
                                                        @verbatim
                                                            {{ errors.first('invoice[items][<?php echo $sellerOrderItem->order_item_id ?>]') }}
                                                        @endverbatim
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <div class="sale-section">
                    <div class="section-content" style="border-bottom: 0">
                        <div class="order-box-container">
                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.billing-address') }}
                                </div>

                                <div class="box-content">

                                    @include ('admin::sales.address', ['address' => $sellerOrder->order->billing_address])

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.shipping-address') }}
                                </div>

                                <div class="box-content">

                                    @include ('admin::sales.address', ['address' => $sellerOrder->order->shipping_address])

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.shipping-method') }}
                                </div>

                                <div class="box-content">

                                    {{ $sellerOrder->order->shipping_title }}

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.payment-method') }}
                                </div>

                                <div class="box-content">
                                    {{ core()->getConfigData('sales.paymentmethods.' . $sellerOrder->order->payment->method . '.title') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {!! view_render_event('marketplace.sellers.account.sales.invoices.create.after', ['sellerOrder' => $sellerOrder]) !!}

        </form>

    </div>

@endsection