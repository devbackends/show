@extends('marketplace::shop.layouts.account')

@section('page_title')
{{ __('marketplace::app.shop.sellers.account.sales.invoices.create-title') }}
@endsection

@section('content')

<div class="settings-page">
    <form method="POST" action="{{ route('marketplace.account.invoices.store', $sellerOrder->order_id) }}" @submit.prevent="onSubmit">
        @csrf
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('marketplace::app.shop.sellers.account.sales.invoices.create-title') }}</p>
            </div>
            <div class="settings-page__header-actions">
                <button type="submit" class="btn btn-primary">
                    {{ __('marketplace::app.shop.sellers.account.sales.invoices.create') }}
                </button>
            </div>
        </div>


        {!! view_render_event('marketplace.sellers.account.sales.invoices.create.before', ['sellerOrder' => $sellerOrder]) !!}

        <div class="sale-container settings-page__body">

            <div class="sale-section">
                <div class="account-table-content profile-page-content">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>{{ __('marketplace::app.shop.sellers.account.sales.invoices.order-id') }}</label>
                                <a href="{{ route('marketplace.account.orders.view', $sellerOrder->order_id) }}">
                                    <p>#{{ $sellerOrder->order_id }}</p>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.placed-on') }}</label>
                                <p>{{ core()->formatDate($sellerOrder->created_at, 'd M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.status') }}</label>
                                <p>{{ $sellerOrder->status_label }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.customer-name') }}</label>
                                <p>{{ $sellerOrder->order->customer_full_name }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>{{ __   ('marketplace::app.shop.sellers.account.sales.orders.email') }}</label>
                                <p>{{ $sellerOrder->order->customer_email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sale-section mb-5">
                    <div class="secton-title mb-4">
                        <p><strong>{{ __('shop::app.customer.account.order.view.products-ordered') }}</strong></p>
                    </div>

                    <div class="section-content">
                        <table class="table">
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

                                        @if (isset($sellerOrderItem->item->additional['attributes']))
                                        <div class="item-options">

                                            @foreach ($sellerOrderItem->item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                            @endforeach

                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $sellerOrderItem->item->qty_ordered }}</td>
                                    <td>
                                        <div class="form-group" :class="[errors.has('invoice[items][{{ $sellerOrderItem->order_item_id }}]') ? 'has-error' : '']">
                                            <input type="text" v-validate="'required|numeric|min:0'" class="form-control" id="invoice[items][{{ $sellerOrderItem->order_item_id }}]" name="invoice[items][{{ $sellerOrderItem->order_item_id }}]" value="{{ ($sellerOrderItem->qty_to_invoice) ? $sellerOrderItem->qty_to_invoice : $sellerOrderItem->item->qty_ordered}}" data-vv-as="&quot;{{ __('admin::app.sales.invoices.qty-to-invoice') }}&quot;" />

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

                <div class="sale-section">
                    <div class="section-content" style="border-bottom: 0">
                        <div class="order-box-container">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('shop::app.customer.account.order.view.billing-address') }}</label>
                                        <p>@include ('admin::sales.address', ['address' => $sellerOrder->order->billing_address])</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('shop::app.customer.account.order.view.shipping-address') }}</label>
                                        <p>@include ('admin::sales.address', ['address' => $sellerOrder->order->shipping_address])</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('shop::app.customer.account.order.view.shipping-method') }}</label>
                                        <p>{{ $sellerOrder->order->shipping_title }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('shop::app.customer.account.order.view.payment-method') }}</label>
                                        <p>{{ core()->getConfigData('sales.paymentmethods.' . $sellerOrder->order->payment->method . '.title') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {!! view_render_event('marketplace.sellers.account.sales.invoices.create.after', ['sellerOrder' => $sellerOrder]) !!}
        </div>
    </form>

</div>

@endsection