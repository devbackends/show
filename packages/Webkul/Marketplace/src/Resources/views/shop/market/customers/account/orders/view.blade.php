@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.view.page-tile', ['order_id' => $order->increment_id]) }}
@endsection


@section('page-detail-wrapper')
    <div class="settings-page">
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p><a href="/customer/account/orders">{{ __('shop::app.customer.account.order.index.title') }}</a> / {{ __('shop::app.customer.account.order.view.page-tile', ['order_id' => $order->increment_id]) }} - Placced on {{ core()->formatDate($order->created_at, 'd M Y') }}</p>
            </div>
            <div class="settings-page__header-actions">
                @php $marketplace_order= app('Webkul\Marketplace\Repositories\OrderRepository')->findWhere(['order_id'=>$order->id]); @endphp
                @if($marketplace_order)

                    @php $marketplace_order=$marketplace_order->first();
                    if($marketplace_order){
                        $seller_id=$marketplace_order->marketplace_seller_id;
                    }else{
                        $seller_id=0;
                    }


                    @endphp

                @else
                    @php $seller_id=0 @endphp
                @endif
                @php $marketplace_seller= app('Webkul\Marketplace\Repositories\SellerRepository')->findWhere(['id'=>$seller_id])->first();
                    $customer_seller_id=$marketplace_seller->customer_id;
                @endphp
                <a id="contact-seller" data-id="{{$order->id}}" data-customersellerid="{{$customer_seller_id}}" href="javascript:;"
                    class="btn btn-primary">
                    Contact Seller
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.view.before', ['order' => $order]) !!}

        <div class="sale-container settings-page__body">
            <tabs>
                <tab name="{{ __('shop::app.customer.account.order.view.info') }}" :selected="true">

                    {{--                        <div class="sale-section">
                                                <div class="section-content">
                                                    <div class="row col-12">
                                                        <label class="mr20">
                                                            {{ __('shop::app.customer.account.order.view.placed-on') }}
                                                        </label>

                                                        <span class="value">
                                                            {{ core()->formatDate($order->created_at, 'd M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>--}}

                    <div class="sale-section mb-5">
                        <p class="mb-4"><strong>{{ __('shop::app.customer.account.order.view.products-ordered') }}</strong></p>

                        <div class="section-content">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.item-status') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.tax-percent') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}">
                                                {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">
                                                {{ $item->name }}

                                                @if (isset($item->additional['attributes']))
                                                    <div class="item-options">

                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach

                                                    </div>
                                                @endif
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">
                                                {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.item-status') }}">
                                                    <span class="qty-row">
                                                        {{ __('shop::app.customer.account.order.view.item-ordered', ['qty_ordered' => $item->qty_ordered]) }}
                                                    </span>

                                                <span class="qty-row">
                                                        {{ $item->qty_invoiced ? __('shop::app.customer.account.order.view.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                                    </span>

                                                <span class="qty-row">
                                                        {{ $item->qty_shipped ? __('shop::app.customer.account.order.view.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                                    </span>

                                                <span class="qty-row">
                                                        {{ $item->qty_refunded ? __('shop::app.customer.account.order.view.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}
                                                    </span>

                                                <span class="qty-row">
                                                        {{ $item->qty_canceled ? __('shop::app.customer.account.order.view.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                                    </span>
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.subtotal') }}">
                                                {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.tax-percent') }}">
                                                {{ number_format($item->tax_percent, 2) }}%
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.tax-amount') }}">
                                                {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                            </td>

                                            <td data-value="{{ __('shop::app.customer.account.order.view.grand-total') }}">
                                                {{ core()->formatPrice($item->total + $item->tax_amount - $item->discount_amount, $order->order_currency_code) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-auto ml-auto">
                                    <div class="totals">
                                        <table class="table-sm">
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                    <td>{{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}</td>
                                                </tr>
                                                @if ($order->haveStockableItems())
                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                    <td>{{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}</td>
                                                </tr>
                                                @endif

                                                @if ($order->base_discount_amount > 0)
                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.discount') }}</td>
                                                    <td>{{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</td>
                                                </tr>
                                                @endif

                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                    <td>{{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="font-weight-bold">{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                    <td class="font-weight-bold">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.total-paid') }}</td>
                                                    <td>{{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.total-refunded') }}</td>
                                                    <td>{{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="font-weight-bold">{{ __('shop::app.customer.account.order.view.total-due') }}</td>
                                                    <td class="font-weight-bold">{{ core()->formatPrice($order->total_due, $order->order_currency_code) }}</td>
                                                </tr>
                                            <tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </tab>

                @if ($order->invoices->count())
                    <tab name="{{ __('shop::app.customer.account.order.view.invoices') }}">

                        @foreach ($order->invoices as $invoice)

                            <div class="sale-section mb-5">
                                <div class="secton-title mb-4">
                                    <p class="mb-0 d-inline-block align-middle"><strong>{{ __('shop::app.customer.account.order.view.individual-invoice', ['invoice_id' => $invoice->id]) }}</strong></p>
                                    <a href="{{ route('customer.orders.print', $invoice->id) }}" class="btn btn-link d-inline-block align-middle">
                                    <i class="far fa-print mr-2"></i>{{ __('shop::app.customer.account.order.view.print') }}</a>
                                </div>
                                <div class="section-content">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($invoice->items as $item)
                                            <tr>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}">
                                                    {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">
                                                    {{ $item->name }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">
                                                    {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}">
                                                    {{ $item->qty }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.subtotal') }}">
                                                    {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.tax-amount') }}">
                                                    {{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}
                                                </td>

                                                <td data-value="{{ __('shop::app.customer.account.order.view.grand-total') }}">
                                                    {{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-auto ml-auto">
                                            <div class="totals">
                                                <table class="table-sm">
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                        <td>{{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                        <td>{{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}</td>
                                                    </tr>

                                                    @if ($order->base_discount_amount > 0)
                                                        <tr>
                                                            <td>{{ __('shop::app.customer.account.order.view.discount') }}</td>
                                                            <td>{{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</td>
                                                        </tr>
                                                    @endif

                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                        <td>{{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="font-weight-bold">{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                        <td class="font-weight-bold">{{ core()->formatPrice($invoice->grand_total, $order->order_currency_code) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    </tab>
                @endif

                @if ($order->shipments->count())
                    <tab name="{{ __('shop::app.customer.account.order.view.shipments') }}">

                        @foreach ($order->shipments as $shipment)

                            <div class="sale-section">
                                <p class="mb-4"><strong>{{ __('shop::app.customer.account.order.view.individual-shipment', ['shipment_id' => $shipment->id]) }}</strong></p>
                                <div class="section-content">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shipment->items as $item)

                                                <tr>
                                                    <td data-value="{{  __('shop::app.customer.account.order.view.SKU') }}">{{ $item->sku }}</td>
                                                    <td data-value="{{  __('shop::app.customer.account.order.view.product-name') }}">{{ $item->name }}</td>
                                                    <td data-value="{{  __('shop::app.customer.account.order.view.qty') }}">{{ $item->qty }}</td>
                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @endforeach

                    </tab>
                @endif

                @if ($order->refunds->count())
                    <tab name="{{ __('shop::app.customer.account.order.view.refunds') }}">

                        @foreach ($order->refunds as $refund)

                            <div class="sale-section">
                                <p class="mb-4"><strong>{{ __('shop::app.customer.account.order.view.individual-refund', ['refund_id' => $refund->id]) }}</strong></p>
                                <div class="section-content">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($refund->items as $item)
                                            <tr>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}">{{ $item->child ? $item->child->sku : $item->sku }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">{{ $item->name }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">{{ core()->formatPrice($item->price, $order->order_currency_code) }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}">{{ $item->qty }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.subtotal') }}">{{ core()->formatPrice($item->total, $order->order_currency_code) }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.tax-amount') }}">{{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}</td>
                                                <td data-value="{{ __('shop::app.customer.account.order.view.grand-total') }}">{{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}</td>
                                            </tr>
                                            @endforeach
                                            @if (! $refund->items->count())
                                            <tr>
                                                <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                            <tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-auto ml-auto">
                                            <div class="totals">
                                                <table class="table-sm">
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                        <td>{{ core()->formatPrice($refund->sub_total, $order->order_currency_code) }}</td>
                                                    </tr>
                                                    @if ($refund->shipping_amount > 0)
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                        <td>{{ core()->formatPrice($refund->shipping_amount, $order->order_currency_code) }}</td>
                                                    </tr>
                                                    @endif
                                                    @if ($refund->discount_amount > 0)
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.discount') }}</td>
                                                        <td>{{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</td>
                                                    </tr>
                                                    @endif
                                                    @if ($refund->tax_amount > 0)
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                        <td>{{ core()->formatPrice($refund->tax_amount, $order->order_currency_code) }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.adjustment-refund') }}</td>
                                                        <td>{{ core()->formatPrice($refund->adjustment_refund, $order->order_currency_code) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('shop::app.customer.account.order.view.adjustment-fee') }}</td>
                                                        <td>{{ core()->formatPrice($refund->adjustment_fee, $order->order_currency_code) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                        <td class="font-weight-bold">{{ core()->formatPrice($refund->grand_total, $order->order_currency_code) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    </tab>
                @endif

                <a href="{{route('rma.customers.allrma')}}"><tab name="Return" ></tab></a>
            </tabs>

            <div class="sale-section">
                <div class="section-content" style="border-bottom: 0">
                    <div class="order-box-container">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('shop::app.customer.account.order.view.billing-address') }}</label>
                                    <p>@include ('admin::sales.address', ['address' => $order->billing_address])</p>
                                </div>
                            </div>
                            @if ($order->shipping_address)
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('shop::app.customer.account.order.view.shipping-address') }}</label>
                                        <p>@include ('admin::sales.address', ['address' => $order->shipping_address])</p>
                                    </div>
                                </div>

                                @if ($order->shipping_address)
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('shop::app.customer.account.order.view.shipping-address') }}</label>
                                            <p>@include ('admin::sales.address', ['address' => $order->shipping_address])</p>
                                        </div>
                                    </div>
                                @endif
                                @if($order->ffl_address)
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>FFL Address</label>
                                            <p>@include ('admin::sales.address', ['address' => $order->ffl_address])</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>FFL Address</label>
                                        <p>@include ('admin::sales.address', ['address' => $order->cart->ffl_address])</p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('shop::app.customer.account.order.view.shipping-method') }}</label>
                                    <p>{{ $order->shipping_title }}</p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('shop::app.customer.account.order.view.payment-method') }}</label>
                                    <p>{{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.view.after', ['order' => $order]) !!}
    </div>
    @include('marketplace::shop.customers.account.orders.contact-seller')


    @push("scripts")
        <script>
            $(document).ready(() => {
            $(document).on('click','li',function() {
                if($(this).children().html().indexOf('Return') >=0){
                    window.location='https://'+window.location.hostname+'/customer/account/rma';
                }
            });
                $('#contact-seller').click(function (e) {
                    e.preventDefault();
                    const contactSellerModal = $('#contactSeller');
                    contactSellerModal.modal('show');
                    const customer_seller_id = $(this).attr('data-customersellerid');
                    $('#from').val(<?= auth()->guard('customer')->user()->id ?>);
                    $('#to').val(customer_seller_id);
                    $('#order_id').val($(this).attr('data-id'));
                });
            });
        </script>
    @endpush
@endsection