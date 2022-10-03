@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.shipments.create-title') }}
@endsection

@section('content')

    <div class="settings-page">

        <form method="POST" action="{{ route('marketplace.account.shipments.store', $sellerOrder->order_id) }}" @submit.prevent="onSubmit">
            @csrf
            <div class="settings-page__header">
                <div class="settings-page__header-title">
                    <p>{{ __('marketplace::app.shop.sellers.account.sales.shipments.create-title') }}</p>
                </div>
                <div class="settings-page__header-actions">
                    <button type="submit" class="btn btn-primary">
                        {{ __('marketplace::app.shop.sellers.account.sales.shipments.create') }}
                    </button>
                </div>
            </div>


            {!! view_render_event('marketplace.sellers.account.sales.shipments.create.before', ['sellerOrder' => $sellerOrder]) !!}

            <div class="sale-container settings-page__body">

                <div class="sale-section">
                    <div class="account-table-content profile-page-content">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.shipments.order-id') }}</label>
                                    <a href="{{ route('marketplace.account.orders.view', $sellerOrder->order_id) }}"><p>#{{ $sellerOrder->order_id }}</p></a>
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
                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.email') }}</label>
                                    <p>{{ $sellerOrder->order->customer_email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sale-section">
                    <div class="section-content">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group" :class="[errors.has('shipment[carrier_title]') ? 'has-error' : '']">
                                    <label for="shipment[carrier_title]" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.sales.shipments.carrier-title') }}</label>
                                    <input type="text" v-validate="'required'" class="form-control" id="shipment[carrier_title]" name="shipment[carrier_title]" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.sales.shipments.carrier-title') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('shipment[carrier_title]')">
                                        @{{ errors.first('shipment[carrier_title]') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group" :class="[errors.has('shipment[track_number]') ? 'has-error' : '']">
                                    <label for="shipment[track_number]" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.sales.shipments.tracking-number') }}</label>
                                    <input type="text" v-validate="'required'" class="form-control" id="shipment[track_number]" name="shipment[track_number]" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.sales.shipments.tracking-number') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has('shipment[track_number]')">
                                        @{{ errors.first('shipment[track_number]') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sale-section mb-5">
                    <div class="secton-title mb-4">
                        <p><strong>{{ __('shop::app.customer.account.order.view.products-ordered') }}</strong></p>
                    </div>
                    <div class="section-content">
                        <order-item-list></order-item-list>
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

            {!! view_render_event('marketplace.sellers.account.sales.shipments.create.after', ['sellerOrder' => $sellerOrder]) !!}

        </form>

    </div>

@endsection

@push('scripts')

    <script type="text/x-template" id="order-item-list-template">

        <div>
            <div class="row">

                <table class="table">
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

                                        @if (isset($sellerOrderItem->item->additional['attributes']))
                                            <div class="item-options">

                                                @foreach ($sellerOrderItem->item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $sellerOrderItem->item->qty_ordered }}</td>
                                    <td>{{ $sellerOrderItem->item->qty_to_ship }}</td>
                                    <td>
                                        <table class="table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-available') }}</th>
                                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-to-ship') }}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                    <tr>

                                                        <td>
                                                            <?php
                                                                $sourceQty = 0;

                                                                $product = $sellerOrderItem->item->type == 'configurable' ? $sellerOrderItem->child->product : $sellerOrderItem->product;
                                                                $sourceQty = $product->productFlat->quantity;

                                                            ?>

                                                            {{ $sourceQty }}
                                                        </td>

                                                        <td>
                                                            <?php $inputName = "shipment[items][$sellerOrderItem->order_item_id]"; ?>

                                                            <div class="control-group" :class="[errors.has('{{ $inputName }}') ? 'has-error' : '']">

                                                                <input type="text" v-validate="'required|numeric|min_value:0|max_value:{{$sourceQty}}'" class="form-control" id="{{ $inputName }}" name="{{ $inputName }}" value="{{$sellerOrderItem->item->qty_to_ship}}" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.sales.shipments.qty-to-ship') }}&quot;"/>

                                                                <span class="control-error" v-if="errors.has('{{ $inputName }}')">
                                                                    @verbatim
                                                                        {{ errors.first('<?php echo $inputName; ?>') }}
                                                                    @endverbatim
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>

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
            }),

            mounted() {
                $('.shipment-source-input').change((e) => {
                    this.source = e.target.value;
                })
            },
        });

        $(document).ready(() => {
            $('.shipment-source-input option[data-default="true"]').prop('selected', true);
            $('.shipment-source-input').trigger('change');
        })
    </script>

@endpush