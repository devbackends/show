@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.shipments.create-title') }}
@endsection

@section('content')

    <div class="account-layout right m10">

        <form method="POST" action="{{ route('marketplace.account.shipments.store', $sellerOrder->order_id) }}" @submit.prevent="onSubmit">
            @csrf
            <div class="account-head">
                <span class="account-heading">
                    {{ __('marketplace::app.shop.sellers.account.sales.shipments.create-title') }}
                </span>

                <div class="account-action">
                    <button type="submit" class="btn btn-lg btn-primary theme-btn">
                        {{ __('marketplace::app.shop.sellers.account.sales.shipments.create') }}
                    </button>
                </div>
                <span></span>
            </div>

            {!! view_render_event('marketplace.sellers.account.sales.shipments.create.before', ['sellerOrder' => $sellerOrder]) !!}

            <div class="sale-container">

                <div class="sale-section">
                    <div class="account-table-content profile-page-content">
                        <div class="table">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ __('marketplace::app.shop.sellers.account.sales.shipments.order-id') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('marketplace.account.orders.view', $sellerOrder->order_id) }}">#{{ $sellerOrder->order_id }}</a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {{ __('marketplace::app.shop.sellers.account.sales.orders.placed-on') }}
                                    </td>

                                    <td>
                                        {{ core()->formatDate($sellerOrder->created_at, 'd M Y') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {{ __('marketplace::app.shop.sellers.account.sales.orders.status') }}
                                    </td>

                                    <td>
                                        {{ $sellerOrder->status_label }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {{ __('marketplace::app.shop.sellers.account.sales.orders.customer-name') }}
                                    </td>

                                    <td>
                                        {{ $sellerOrder->order->customer_full_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {{ __('marketplace::app.shop.sellers.account.sales.orders.email') }}
                                    </td>

                                    <td>
                                        {{ $sellerOrder->order->customer_email }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="sale-section">
                    <div class="section-content">

                        <div class="form-group" :class="[errors.has('shipment[carrier_title]') ? 'has-error' : '']">
                            <label for="shipment[carrier_title]" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.sales.shipments.carrier-title') }}</label>
                            <input type="text" v-validate="'required'" class="form-style" id="shipment[carrier_title]" name="shipment[carrier_title]" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.sales.shipments.carrier-title') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('shipment[carrier_title]')">
                                @{{ errors.first('shipment[carrier_title]') }}
                            </span>
                        </div>

                        <div class="form-group" :class="[errors.has('shipment[track_number]') ? 'has-error' : '']">
                            <label for="shipment[track_number]" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.sales.shipments.tracking-number') }}</label>
                            <input type="text" v-validate="'required'" class="form-style" id="shipment[track_number]" name="shipment[track_number]" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.sales.shipments.tracking-number') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('shipment[track_number]')">
                                @{{ errors.first('shipment[track_number]') }}
                            </span>
                        </div>

                    </div>
                </div>

                <div class="sale-section">
                    <div class="secton-title">
                        <span>{{ __('shop::app.customer.account.order.view.products-ordered') }}</span>
                    </div>

                    <div class="section-content">

                        <order-item-list></order-item-list>

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

            {!! view_render_event('marketplace.sellers.account.sales.shipments.create.after', ['sellerOrder' => $sellerOrder]) !!}

        </form>

    </div>

@endsection

@push('scripts')

    <script type="text/x-template" id="order-item-list-template">

        <div>
            <div class="table">

                <table>
                    <thead>
                        <tr>
                            <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.product-name') }}</th>
                            <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-ordered') }}</th>
                            <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-to-ship') }}</th>
                            <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.available-sources') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($sellerOrder->items as $sellerOrderItem)
                            @if ($sellerOrderItem->item->qty_to_ship > 0 && $sellerOrderItem->product)
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
                                    <td>{{ $sellerOrderItem->item->qty_to_ship }}</td>
                                    <td>

                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.source') }}</th>
                                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-available') }}</th>
                                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-to-ship') }}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach (core()->getCurrentChannel()->inventory_sources as $inventorySource)
                                                    <tr>
                                                        <td>
                                                            {{ $inventorySource->name }}
                                                        </td>

                                                        <td>
                                                            <?php
                                                                $sourceQty = 0;

                                                                $product = $sellerOrderItem->item->type == 'configurable' ? $sellerOrderItem->child->product->product : $sellerOrderItem->product->product;

                                                                foreach ($product->inventories as $inventory) {
                                                                    if ($inventory->inventory_source_id == $inventorySource->id && $inventory->vendor_id == $sellerOrder->marketplace_seller_id) {
                                                                        $sourceQty = $inventory->qty;
                                                                        break;
                                                                    }
                                                                }
                                                            ?>

                                                            {{ $sourceQty }}
                                                        </td>

                                                        <td>
                                                            <?php $inputName = "shipment[items][$sellerOrderItem->order_item_id][$inventorySource->id]"; ?>

                                                            <div class="control-group" :class="[errors.has('{{ $inputName }}') ? 'has-error' : '']">

                                                                <input type="text" v-validate="'required|numeric|min_value:0|max_value:{{$sourceQty}}'" class="control" id="{{ $inputName }}" name="{{ $inputName }}" value="0" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-to-ship') }}&quot;" :disabled="source != '{{ $inventorySource->id }}'"/>

                                                                <span class="control-error" v-if="errors.has('{{ $inputName }}')">
                                                                    @verbatim
                                                                        {{ errors.first('<?php echo $inputName; ?>') }}
                                                                    @endverbatim
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </script>

    <script>
        Vue.component('order-item-list', {

            template: '#order-item-list-template',

            inject: ['$validator'],

            data: () => ({
                source: ""
            })
        });
    </script>

@endpush