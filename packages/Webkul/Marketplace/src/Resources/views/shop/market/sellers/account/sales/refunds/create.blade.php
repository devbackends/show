@extends('marketplace::shop.layouts.account')

@section('page_title')
    create refund
@endsection

@section('content')

    <refund-items></refund-items>

@stop

@push('scripts')
    <script type="text/x-template" id="refund-items-template">
        <div>
            <div class="account-layout pl-5">
                <form method="POST" action="{{ route('marketplace.account.refunds.store', $order->id) }}"
                      @submit.prevent="onSubmit">
                    @csrf()

                    <div class="account-head">
                        <div class="page-title">
                            <h1 class="h3"><i class="icon angle-left-icon back-link"
                                              onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i> {{ __('admin::app.sales.refunds.add-title') }}
                            </h1>
                        </div>

                        <div class="account-action">
                            <button type="submit" class="btn btn-primary">
                                {{ __('admin::app.sales.refunds.save-btn-title') }}
                            </button>
                        </div>
                    </div>

                    <div class="sale-container">
                        @foreach ($availableProcessors as $availableProcessor)
                            @if($availableProcessor['title'] == 'Authorize')
                                <div class="alert alert-danger" role="alert">
                                    Currently, we only support manual refunds through Authorize.net. Submitting this form will only update the order and email the customer. To refund the customer's money, you will need to login to your Authroize.net account and issue the refund there.
                                </div>
                            @endif
                        @endforeach

                        <div class="form-group mb-5">
                            <label for="refund_processor" class="required">Refund Processor</label>

                            <select v-model="refund_processor" v-validate="'required'" class="form-control" style="width: auto;"
                                    name="refund_processor" id="refund_processor"
                                    data-vv-as="&quot;Refund Processor&quot;">

                                <option value="manual" >Manual</option>

                                @foreach ($availableProcessors as $availableProcessor)
                                    @if($availableProcessor['title'] != 'Authorize')
                                    <option
                                        value="{{ $availableProcessor['code'] }}">{{ $availableProcessor['title'] }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>

                        <div class="info-disclaimer mb-5" v-if="refund_processor=='manual'">
                            <div class="row">
                                <div class="col-auto">
                                    <i class="far fa-exclamation-triangle"></i>
                                </div>
                                <div class="col">
                                    <p class="mb-0">This will only update the order status on 2agunshow.com. In order to
                                        refund the user money please coordinate with them directly.</p>
                                </div>
                            </div>
                        </div>

                    <!-- <p class="font-paragraph-big">{{ __('admin::app.sales.orders.order-and-account') }}</p> -->
                        <p class="font-paragraph-big">{{ __('admin::app.sales.orders.order-info') }}</p>
                        <div class="row mb-5">
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.refunds.order-id') }}</label>
                                    <a href="{{ route('marketplace.account.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.orders.order-date') }}</label>
                                    <p>{{ $order->created_at }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.orders.order-status') }}</label>
                                    <p>{{ $order->status_label }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.orders.channel') }}</label>
                                    <p>{{ $order->channel_name }}</p>
                                </div>
                            </div>
                        </div>

                        <p class="font-paragraph-big">{{ __('admin::app.sales.orders.account-info') }}</p>
                        <div class="row mb-5">
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.orders.customer-name') }}</label>
                                    <p>{{ $order->customer_full_name }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.orders.email') }}</label>
                                    <p>{{ $order->customer_email }}</p>
                                </div>
                            </div>
                        </div>

                        <p class="font-paragraph-big">{{ __('admin::app.sales.orders.address') }}</p>
                        <div class="row mb-5">
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label>{{ __('admin::app.sales.orders.billing-address') }}</label>
                                    <p>@include ('admin::sales.address', ['address' => $order->billing_address])</p>
                                </div>
                            </div>
                            @if ($order->shipping_address)
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('admin::app.sales.orders.shipping-address') }}</label>
                                        <p>@include ('admin::sales.address', ['address' => $order->shipping_address])</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    <!-- <p class="font-paragraph-big">{{ __('admin::app.sales.orders.payment-and-shipping') }}</p> -->
                        <div class="row">
                            <div class="col-12 col-md">
                                <p class="font-paragraph-big">{{ __('admin::app.sales.orders.payment-info') }}</p>
                                <div class="row">
                                    <div class="col-12 col-md">
                                        <div class="form-group">
                                            <label>{{ __('admin::app.sales.orders.payment-method') }}</label>
                                            <p>{{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md">
                                        <div class="form-group">
                                            <label>{{ __('admin::app.sales.orders.currency') }}</label>
                                            <p>{{ $order->order_currency_code }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md">
                                <p class="font-paragraph-big">{{ __('admin::app.sales.orders.shipping-info') }}</p>
                                <div class="row">
                                    <div class="col-12 col-md">
                                        <div class="form-group">
                                            <label>{{ __('admin::app.sales.orders.shipping-method') }}</label>
                                            <p>{{ $order->shipping_title }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md">
                                        <div class="form-group">
                                            <label>{{ __('admin::app.sales.orders.shipping-price') }}</label>
                                            <p>{{ core()->formatBasePrice($order->base_shipping_amount) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="font-paragraph-big">{{ __('admin::app.sales.orders.products-ordered') }}</p>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                                <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                                <th>{{ __('admin::app.sales.orders.item-status') }}</th>
                                <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                                <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                @if ($order->base_discount_amount > 0)
                                    <th>{{ __('admin::app.sales.orders.discount-amount') }}</th>
                                @endif
                                <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                                <th>{{ __('admin::app.sales.refunds.qty-ordered') }}</th>
                                <th>{{ __('admin::app.sales.refunds.qty-to-refund') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ Webkul\Product\Helpers\ProductType::hasVariants($item->type) ? $item->child->sku : $item->sku }}</td>

                                    <td>
                                        {{ $item->name }}

                                        @if (isset($item->additional['attributes']))
                                            <div class="item-options">

                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }}
                                                        : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                <span class="qty-row">
                                    {{ $item->qty_ordered ? __('admin::app.sales.orders.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}
                                </span>

                                        <span class="qty-row">
                                    {{ $item->qty_invoiced ? __('admin::app.sales.orders.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                </span>

                                        <span class="qty-row">
                                    {{ $item->qty_shipped ? __('admin::app.sales.orders.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                </span>

                                        <span class="qty-row">
                                    {{ $item->qty_refunded ? __('admin::app.sales.orders.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}
                                </span>

                                        <span class="qty-row">
                                    {{ $item->qty_canceled ? __('admin::app.sales.orders.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                </span>
                                    </td>

                                    <td>{{ core()->formatBasePrice($item->base_price) }}</td>

                                    <td>{{ core()->formatBasePrice($item->base_tax_amount) }}</td>

                                    @if ($order->base_discount_amount > 0)
                                        <td>{{ core()->formatBasePrice($item->base_discount_amount) }}</td>
                                    @endif

                                    <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</td>

                                    <td>{{ $item->qty_ordered }}</td>

                                    <td>
                                        <div class="form-group mb-0"
                                             :class="[errors.has('refund[items][{{ $item->id }}]') ? 'has-error' : '']">
                                            <input type="text" v-validate="'required|numeric|min:0'"
                                                   class="form-control" id="refund[items][{{ $item->id }}]"
                                                   name="refund[items][{{ $item->id }}]"
                                                   v-model="refund.items[{{ $item->id }}]"
                                                   data-vv-as="&quot;{{ __('admin::app.sales.refunds.qty-to-refund') }}&quot;"/>

                                            <span class="control-error"
                                                  v-if="errors.has('refund[items][{{ $item->id }}]')">
                                        @verbatim
                                                    {{ errors.first('refund[items][<?php echo $item->id ?>]') }}
                                                @endverbatim
                                    </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-12 col-md-auto">
                                <div class="totals">
                                    <table class="table-sm" v-if="refund.summary">
                                        <tr>
                                            <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                                            <td>-</td>
                                            <td>@{{ refund.summary.subtotal.formated_price }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin::app.sales.orders.discount') }}</td>
                                            <td>-</td>
                                            <td>-@{{ refund.summary.discount.formated_price }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin::app.sales.refunds.refund-shipping') }}</td>
                                            <td>-</td>
                                            <td>
                                                <div class="form-group mb-0"
                                                     :class="[errors.has('refund[shipping]') ? 'has-error' : '']"
                                                     style="width: 100px; margin-bottom: 0;">
                                                    <input type="text"
                                                           v-validate="'required|min_value:0|max_value:{{$order->base_shipping_invoiced - $order->base_shipping_refunded}}'"
                                                           class="form-control" id="refund[shipping]"
                                                           name="refund[shipping]"
                                                           v-model="refund.summary.shipping.price"
                                                           data-vv-as="&quot;{{ __('admin::app.sales.refunds.refund-shipping') }}&quot;"
                                                           style="width: 100%; margin: 0"/>

                                                    <span class="control-error" v-if="errors.has('refund[shipping]')">
                                            @{{ errors.first('refund[shipping]') }}
                                        </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin::app.sales.refunds.adjustment-refund') }}</td>
                                            <td>-</td>
                                            <td>
                                                <div class="form-group mb-0"
                                                     :class="[errors.has('refund[adjustment_refund]') ? 'has-error' : '']"
                                                     style="width: 100px; margin-bottom: 0;">
                                                    <input type="text" v-validate="'required|min_value:0'"
                                                           class="form-control" id="refund[adjustment_refund]"
                                                           name="refund[adjustment_refund]" value="0"
                                                           data-vv-as="&quot;{{ __('admin::app.sales.refunds.adjustment-refund') }}&quot;"
                                                           style="width: 100%; margin: 0"/>

                                                    <span class="control-error"
                                                          v-if="errors.has('refund[adjustment_refund]')">
                                            @{{ errors.first('refund[adjustment_refund]') }}
                                        </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin::app.sales.refunds.adjustment-fee') }}</td>
                                            <td>-</td>
                                            <td>
                                                <div class="form-group"
                                                     :class="[errors.has('refund[adjustment_fee]') ? 'has-error' : '']"
                                                     style="width: 100px; margin-bottom: 0;">
                                                    <input type="text" v-validate="'required|min_value:0'"
                                                           class="form-control" id="refund[adjustment_fee]"
                                                           name="refund[adjustment_fee]" value="0"
                                                           data-vv-as="&quot;{{ __('admin::app.sales.refunds.adjustment-fee') }}&quot;"
                                                           style="width: 100%; margin: 0"/>

                                                    <span class="control-error"
                                                          v-if="errors.has('refund[adjustment_fee]')">
                                            @{{ errors.first('refund[adjustment_fee]') }}
                                        </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin::app.sales.orders.tax') }}</td>
                                            <td>-</td>
                                            <td>@{{ refund.summary.tax.formated_price }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">{{ __('admin::app.sales.orders.grand-total') }}</td>
                                            <td class="font-weight-bold">-</td>
                                            <td class="font-weight-bold">@{{ refund.summary.grand_total.formated_price
                                                }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md text-right">
                                <button type="button" class="btn btn-primary" @click="updateQty">
                                    {{ __('admin::app.sales.refunds.update-qty') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </script>

    <script>
        Vue.component('refund-items', {
            template: '#refund-items-template',

            inject: ['$validator'],

            data: function () {
                return {
                    refund_processor: '{{empty($availableProcessors) ? 'manual': null }}',
                    refund: {
                        items: {},

                        summary: null
                    }
                }
            },

            mounted: function () {
                @foreach ($order->items as $item)
                    this.refund.items[{{$item->id}}] = {{ $item->qty_to_refund }};
                @endforeach

                    this.updateQty();
            },

            methods: {
                updateQty: function () {
                    var this_this = this;

                    this.$http.post("{{ route('marketplace.account.refunds.update_qty', $order->id) }}", this.refund.items)
                        .then(function (response) {
                            if (!response.data) {
                                window.flashMessages = [{
                                    'type': 'alert-error',
                                    'message': "{{ __('admin::app.sales.refunds.invalid-qty') }}"
                                }];

                                this_this.$root.addFlashMessages()
                            } else {
                                this_this.refund.summary = response.data;
                            }
                        })
                        .catch(function (error) {
                        })
                }
            }
        });
    </script>
@endpush