@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.orders.view-title', ['order_id' => $sellerOrder->order_id]) }}
@endsection

@section('content')

    <div class="settings-page">
    <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p><a href="/marketplace/account/sales/orders">Orders</a> / {{ __('marketplace::app.shop.sellers.account.sales.orders.view-title', ['order_id' => $sellerOrder->order_id]) }}</p>
            </div>
            <div class="settings-page__header-actions">

                @if ($sellerOrder->canCancel() && ! $sellerOrder->canRefund())
                    @if(!$sellerOrder->order()->get()->first()->cashsale_status)
                        @if($sellerOrder->seller_payout_status!='paid')
                            <button type="button" class="btn btn-outline-gray-dark ml-2" id="cancel_button" data-orderid="{{$sellerOrder->order_id}}" >{{ __('admin::app.sales.orders.cancel-btn-title') }}</button>
                        @endif
                    @endif

                @endif

                @if($sellerOrder->isCashSale() && $sellerOrder->canCancel())
                   @if(!$sellerOrder->order()->get()->first()->cashsale_status)
                            <a href="{{ route('marketplace.account.orders.pay-cashsale.create', $sellerOrder->order_id) }}"
                               class="btn btn-primary ml-2">
                                Record Payment
                            </a>
                   @endif
                @endif
                @if (core()->getConfigData('marketplace.settings.general.can_create_invoice') && $sellerOrder->canInvoice())
                    <a href="{{ route('marketplace.account.invoices.create', $sellerOrder->order_id) }}"
                       class="btn btn-primary ml-2">
                        {{ __('admin::app.sales.orders.invoice-btn-title') }}
                    </a>
                @endif
                @if ($sellerOrder->canRefund())
                        {{--@if(!$sellerOrder->isCashSale())--}}
                    <a href="{{ route('marketplace.account.refunds.create', $sellerOrder->order_id) }}" class="btn btn-primary ml-2">
                        {{ __('admin::app.sales.orders.refund-btn-title') }}
                    </a>
                       {{--@endif--}}
                @endif


                @if (core()->getConfigData('marketplace.settings.general.can_create_shipment') && $sellerOrder->canShip())
                    <a href="{{ route('marketplace.account.shipments.create', $sellerOrder->order_id) }}"
                       class="btn btn-primary ml-2">
                        {{ __('admin::app.sales.orders.shipment-btn-title') }}
                    </a>
                @endif


                @php $order= app('Webkul\Sales\Repositories\OrderRepository')->findWhere(['id'=>$sellerOrder->order_id])->first(); @endphp
                @if($order)
                    <a id="contact-seller" data-id="{{$sellerOrder->order_id}}" data-customerid="{{$order->customer_id}}" href="javascript:;"
                       class="btn btn-primary ml-2">
                       <i class="far fa-comment-alt-lines mr-2"></i>Contact Customer
                    </a>
                @endif
            </div>
        </div>
 
        {!! view_render_event('marketplace.sellers.account.sales.orders.view.before', ['sellerOrder' => $sellerOrder]) !!}

        <div class="sale-container settings-page__body">

            <tabs>
                <tab name="{{ __('marketplace::app.shop.sellers.account.sales.orders.info') }}" :selected="true">

                    <div class="sale-section">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.placed-on') }}</label>
                                    <p>{{ core()->formatDate($sellerOrder->created_at, 'd M Y') }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.status') }}</label>
                                    <p>{{ $sellerOrder->status_label }}</p>
                                </div>
                            </div>
                            @if($sellerOrder->isCashSale())
                                @if($sellerOrder->order()->get()->first()->cashsale_status)
                            <div class="col">
                                <div class="form-group">
                                    <label>Cashsale</label>
                                    <p>Paid</p>
                                </div>
                            </div>
                                @endif
                            @endif
                            @if($sellerOrder->isCashSale())
                                @if($sellerOrder->order()->get()->first()->cashsale_transaction_number)
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Check/Transaction #</label>
                                            <p>{{$sellerOrder->order()->get()->first()->cashsale_transaction_number}}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.customer-name') }}</label>
                                    <p>{{ $sellerOrder->order->customer_full_name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.email') }}</label>
                                    <p>{{ $sellerOrder->order->customer_email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($sellerOrder->isCashSale())
                                @if($sellerOrder->order()->get()->first()->notes)
                                    <div class="col-9">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <p>{{$sellerOrder->order()->get()->first()->notes}}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>

                    <div class="sale-section mb-5">
                        <p class="mb-4">
                            <strong>{{ __('shop::app.customer.account.order.view.products-ordered') }}</strong></p>

                        <div class="section-content">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.item-status') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                    <th>{{ __('marketplace::app.shop.sellers.account.sales.orders.admin-commission') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.tax-percent') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                    <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($sellerOrder->items as $sellerOrderItem)
                                    <tr>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}">
                                            {{ $sellerOrderItem->item->type == 'configurable' ? $sellerOrderItem->item->child->sku : $sellerOrderItem->item->sku }}
                                        </td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">
                                            {{ $sellerOrderItem->item->name }}

                                            {{-- @if ($html = $sellerOrderItem->item->getOptionDetailHtml())
                                                <p>{{ $html }}</p>
                                            @endif--}}
                                        </td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">{{ core()->formatPrice($sellerOrderItem->item->price, $sellerOrder->order->order_currency_code) }}</td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.item-status') }}">
                                                <span class="qty-row">
                                                    {{ __('shop::app.customer.account.order.view.item-ordered', ['qty_ordered' => $sellerOrderItem->item->qty_ordered]) }}
                                                </span>

                                            <span class="qty-row">
                                                    {{ $sellerOrderItem->item->qty_invoiced ? __('shop::app.customer.account.order.view.item-invoice', ['qty_invoiced' => $sellerOrderItem->item->qty_invoiced]) : '' }}
                                                </span>

                                            <span class="qty-row">
                                                    {{ $sellerOrderItem->item->qty_shipped ? __('shop::app.customer.account.order.view.item-shipped', ['qty_shipped' => $sellerOrderItem->item->qty_shipped]) : '' }}
                                                </span>

                                            <span class="qty-row">
                                                    {{ $sellerOrderItem->item->qty_canceled ? __('shop::app.customer.account.order.view.item-canceled', ['qty_canceled' => $sellerOrderItem->item->qty_canceled]) : '' }}
                                                </span>
                                        </td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.subtotal') }}">{{ core()->formatPrice($sellerOrderItem->item->total, $sellerOrder->order->order_currency_code) }}</td>
                                        <td data-value="{{ __('marketplace::app.shop.sellers.account.sales.orders.admin-commission') }}">{{ core()->formatPrice($sellerOrderItem->commission, $sellerOrder->order->order_currency_code) }}</td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.tax-percent') }}">{{ number_format($sellerOrderItem->item->tax_percent, 2) }}
                                            %
                                        </td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.tax-amount') }}">{{ core()->formatPrice($sellerOrderItem->item->tax_amount, $sellerOrder->order->order_currency_code) }}</td>
                                        <td data-value="{{ __('shop::app.customer.account.order.view.grand-total') }}">{{ core()->formatPrice($sellerOrderItem->item->total + $sellerOrderItem->item->tax_amount, $sellerOrder->order->order_currency_code) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="totals">
                                        <table class="table-sm">
                                            <tbody>
                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                <td>{{ core()->formatPrice($sellerOrder->sub_total, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                <td>{{ core()->formatPrice($sellerOrder->shipping_amount, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                <td>{{ core()->formatPrice($sellerOrder->tax_amount, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td class="font-weight-bold">{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                <td class="font-weight-bold">{{ core()->formatPrice($sellerOrder->grand_total, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.total-paid') }}</td>
                                                <td>{{ core()->formatPrice($sellerOrder->grand_total_invoiced, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.total-refunded') }}</td>
                                                <td>{{ core()->formatPrice($sellerOrder->grand_total_refunded, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.total-due') }}</td>
                                                <td>{{ core()->formatPrice($sellerOrder->total_due, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td> {{ __('marketplace::app.shop.sellers.account.sales.orders.total-seller-amount') }}
                                                </td>
                                                <td>{{ core()->formatPrice($sellerOrder->seller_total, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('marketplace::app.shop.sellers.account.sales.orders.total-admin-commission') }}
                                                </td>
                                                <td>{{ core()->formatPrice($sellerOrder->commission, $sellerOrder->order->order_currency_code) }}</td>
                                            </tr>
                                            <tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                        </div>
                    </div>

                </tab>

                @if ($sellerOrder->invoices->count())
                    <tab name="{{ __('marketplace::app.shop.sellers.account.sales.orders.invoices') }}">

                        @foreach ($sellerOrder->invoices as $sellerInvoice)

                            <div class="sale-section mb-5">
                                <div class="secton-title mb-4">
                                    <p class="mb-0 d-inline-block align-middle">
                                        <strong>{{ __('shop::app.customer.account.order.view.individual-invoice', ['invoice_id' => $sellerInvoice->invoice_id]) }}</strong>
                                    </p>
                                    <a href="{{ route('marketplace.account.invoices.print', $sellerInvoice->marketplace_order_id ) }}"
                                       class="btn btn-link d-inline-block align-middle">
                                        <i class="far fa-print mr-2"></i>{{ __('shop::app.customer.account.order.view.print') }}
                                    </a>
                                </div>

                                <div class="section-content">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach ($sellerInvoice->items as $sellerInvoiceItem)
                                            <?php $baseInvoiceItem = $sellerInvoiceItem->item; ?>
                                            <tr>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">{{ $baseInvoiceItem->name }}</td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">
                                                    {{ core()->formatPrice($baseInvoiceItem->price, $sellerOrder->order->order_currency_code) }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}">{{ $baseInvoiceItem->qty }}</td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.subtotal') }}">
                                                    {{ core()->formatPrice($baseInvoiceItem->total, $sellerOrder->order->order_currency_code) }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.tax-amount') }}">
                                                    {{ core()->formatPrice($baseInvoiceItem->tax_amount, $sellerOrder->order->order_currency_code) }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.grand-total') }}">
                                                    {{ core()->formatPrice($baseInvoiceItem->total + $baseInvoiceItem->tax_amount, $sellerOrder->order->order_currency_code) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="totals">
                                                <table class="table-sm">
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                        <td>{{ core()->formatPrice($sellerInvoice->sub_total, $sellerOrder->order->order_currency_code) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                        <td>{{ core()->formatPrice($sellerInvoice->shipping_amount, $sellerOrder->order->order_currency_code) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                        <td>{{ core()->formatPrice($sellerInvoice->tax_amount, $sellerOrder->order->order_currency_code) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="font-weight-bold">{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                        <td class="font-weight-bold">{{ core()->formatPrice($sellerInvoice->grand_total, $sellerOrder->order->order_currency_code) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </tab>
                @endif

                @if ($sellerOrder->shipments->count())
                    <tab name="{{ __('marketplace::app.shop.sellers.account.sales.orders.shipments') }}">

                        @foreach ($sellerOrder->shipments as $sellerShipment)

                            <div class="sale-section mb-5">
                                <div class="secton-title mb-4">
                                    <p>
                                        <strong>{{ __('shop::app.customer.account.order.view.individual-shipment', ['shipment_id' => $sellerShipment->shipment_id]) }}</strong>
                                    </p>
                                </div>

                                <div class="section-content">

                                    <div class="row">
                                        @if ($sellerShipment->shipment->inventory_source)
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.inventory-source') }}</label>
                                                    <p>{{ $sellerShipment->shipment->inventory_source->name }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.carrier-title') }}</label>
                                                <p>{{ $sellerShipment->shipment->carrier_title }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('marketplace::app.shop.sellers.account.sales.orders.tracking-number') }}</label>
                                                <p>{{ $sellerShipment->shipment->track_number }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($sellerShipment->items as $sellerShipmentItem)

                                            <tr>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}">{{ $sellerShipmentItem->item->sku }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">{{ $sellerShipmentItem->item->name }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}">{{ $sellerShipmentItem->item->qty }}</td>
                                            </tr>

                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        @endforeach
                    </tab>
                @endif
            </tabs>

            <div class="sale-section">
                <div class="section-content">
                    <div class="order-box-container">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('shop::app.customer.account.order.view.billing-address') }}</label>
                                    <p>@include ('admin::sales.address', ['address' => $sellerOrder->order->billing_address])</p>
                                </div>
                            </div>
                            @if($sellerOrder->order->shipping_address)
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('shop::app.customer.account.order.view.shipping-address') }}</label>
                                        <p>@include ('admin::sales.address', ['address' => $sellerOrder->order->shipping_address])</p>
                                    </div>
                                </div>
                            @endif
                            @if($sellerOrder->order->ffl_address)
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>FFL Address</label>
                                        <p>@include ('admin::sales.address', ['address' => $sellerOrder->order->ffl_address])</p>
                                    </div>
                                </div>
                            @endif
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

        {!! view_render_event('marketplace.sellers.account.sales.orders.view.after', ['sellerOrder' => $sellerOrder]) !!}

    </div>
    @include('marketplace::shop.customers.account.orders.contact-seller')
    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#contact-seller').click(function (e) {
                    e.preventDefault();
                    const contactSellerModal = $('#contactSeller');
                    contactSellerModal.modal('show');
                    const customerid = $(this).attr('data-customerid');
                    $('#from').val(<?= auth()->guard('customer')->user()->id ?>);
                    $('#to').val(customerid);
                    $('#order_id').val($(this).attr('data-id'));
                })
                $('#cancel_button').click(function (e) {
                   if(confirm('Are you sure you want to cancel order')){
                       const order_id=$(this).data('orderid');
                       window.location.href = '/marketplace/account/sales/orders/cancel/'+order_id;
                   }
                });
            })
        </script>
    @endpush
@endsection

