@extends('shop::customers.account.index')

@section('page_title')
    {{ __('rma::app.shop.customer.title') }}
@endsection

@section('page-detail-wrapper')
@if (auth()->guard('customer')->user())
    @section('page-detail-wrapper')
    <div class="settings-page">
@else
    @section('content-wrapper')
    <div class="settings-page">
@endif
@csrf()
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('rma::app.shop.customer-rma-create.heading') }}</p>
            </div>
            <div class="settings-page__header-actions">
                <button type="submit" class="btn btn-primary" onClick="formValidation()">
                {{ __('rma::app.general.create') }}
                </button>
            </div>
        </div>

        <div class="settings-page__body">
            <form
                method="POST"
                @submit.prevent="onSubmit"
                enctype="multipart/form-data"
                action="{{ route('rma.customers.store') }}">

                <option-wrapper></option-wrapper>

                <div class="sale-container">
                <div class="form-group">
                            <label>{{ __('rma::app.shop.customer-rma-create.images') }}</label>
                            <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                                input-name="images" :multiple="true">
                            </image-wrapper>
                        </div>

                    <input type="hidden" name="email" value="{{ $customerEmail }}">
                    <input type="hidden" name="name" value="{{ $customerName }}">
                    <input type="hidden" name="token" value="{!! csrf_token() !!} ">

                    <div class="form-group" :class="[errors.has('information') ? 'has-error' : '']">
                                <label>{{ __('rma::app.shop.customer-rma-create.information') }}</label>
                                <textarea class="form-control" id="information" name="information"></textarea>
                            </div>
                </div>
            </form>
        </div>

    </div>
@stop

@push('scripts')
    <script type="text/x-template" id="options-template">
        <div class="sale-container">
            <div>
                <div class="sale-section">
                    <div class="form-group" :class="[errors.has('order_id') ? 'has-error' : '']">
                        <label>{{ __('rma::app.shop.customer-rma-create.orders') }}</label>

                        <select
                            id="orderItem"
                            class="form-control"
                            name="order_id"
                            v-validate="'required'"
                            @change="getResolutions($event)"
                            data-vv-as="&quot;{{ __('rma::app.shop.validation.order_id') }}&quot;">

                            <option :value="null" >{{ __('rma::app.shop.default-option.select-order') }}</option>
                            <option v-for="(order, index) in orders" :value="order.id">
                                @{{ '#' + order.id }}, $@{{ order.grand_total}}
                            </option>
                        </select>

                        <span class="control-error" v-if="errors.has('order_id')">
                            @{{ errors.first('order_id') }}
                        </span>
                    </div>

                    <div
                        class="form-group"
                        v-if="resolutions != ''"
                        :class="[errors.has('resolution') ? 'has-error' : '']">

                        <label>{{ __('rma::app.shop.customer-rma-create.resolution') }}</label>

                        <select
                            class="form-control"
                            id="resolution"
                            name="resolution"
                            v-model="resolution"
                            v-validate="'required'"
                            @change="getOrderByResolution($event)"
                            data-vv-as="&quot;{{ __('rma::app.shop.validation.resolution') }}&quot;">

                            <option :value="null">
                                {{ __('rma::app.shop.default-option.select-resolution') }}
                            </option>

                            <option v-for="selectResolutionByOrder in resolutions" :value="selectResolutionByOrder">
                                @{{ selectResolutionByOrder }}
                            </option>
                        </select>

                        <span class="control-error" v-if="errors.has('resolution')">
                            @{{ errors.first('resolution') }}
                        </span>
                    </div>

                    <div
                        v-if="orderStatus"
                        class="form-group"
                        :class="[errors.has('order_status') ? 'has-error' : '']">

                        <label>{{ __('rma::app.shop.customer-rma-create.order_status') }}</label>

                        <select class="form-control" id=""  name="order_status" v-validate="'required'" data-vv-as="&quot;{{ __('rma::app.shop.validation.order_status') }}&quot;">
                            <option v-for="orderStatusOptions in orderStatus" :value="orderStatusOptions">
                                @{{ orderStatusOptions }}
                            </option>
                        </select>

                        <span class="control-error" v-if="errors.has('order_status')">
                            @{{ errors.first('order_status') }}
                        </span>
                    </div>
                </div>

                <div class="sale-section">
                    <label>{{ __('rma::app.shop.customer-rma-create.item-ordered') }}</label>
                    <div class="section-content">
                        <div class="table">
                            <table class="w-100">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>
                                            <input type='checkbox' @click='checkAll()' id="checkbox" v-model='isCheckAll' />
                                        </th>

                                        <th>
                                            {{ __('rma::app.shop.customer-rma-create.image') }}
                                        </th>

                                        <th style="max-width: 100%; width: 20%;">
                                            {{ __('rma::app.shop.customer-rma-create.product') }}
                                        </th>

                                        <th>{{ __('rma::app.shop.customer-rma-create.sku') }}</th>

                                        <th>{{ __('rma::app.shop.customer-rma-create.price') }}</th>

                                        <th>{{ __('rma::app.shop.customer-rma-create.quantity') }}</th>

                                        <th>{{ __('rma::app.shop.customer-rma-create.reason') }}</th>
                                    </tr>
                                </thead>

                                <tbody style="text-align: center;" v-if="orderItems.length != 0 && resolutions != null">
                                    <tr v-for="(orderData,index) in orderItems">
                                        <td class="no-padding">
                                            <input type='checkbox' id="checkboxSingle" name="order_item_id[]"  v-bind:value="orderData.id" v-model='selected' @change='updateCheckall(); getId($event)'>
                                        </td>

                                        <td>
                                            <img style="height: auto; max-width: 30%;" v-if="productImageCounts > 0" :src="productImage[orderData.product_id]['medium_image_url']">

                                            <img v-else style="max-width: 100%;max-height: 50%;" src="{{  url('vendor/webkul/ui/assets/images/product/small-product-placeholder.png') }}">
                                        </td>

                                        <td style="width: auto;" >
                                            <span v-if="orderData.type == 'configurable' && child[orderData.id]">
                                                @{{ child[orderData.id].name }}
                                                <div>@{{ child[orderData.id].attribute }}</div>
                                            </span>
                                            <span v-else>
                                                @{{ orderData.name }}
                                            </span>

                                            <li style="display:inline;"  v-if="html.length != 0" v-html="html[orderData.id]">
                                            @{{ html[orderData.id] }}
                                            </li>
                                        </td>

                                        <td>
                                            @{{  orderData.type == 'configurable' ? child[orderData.id] ? child[orderData.id].sku : orderData.sku : orderData.sku }}</td>
                                        </td>

                                        <td>@{{ orderData.price }}</td>

                                        <td>
                                            <div class="control-group full-width" :class="[errors.has('quantity[' + orderItems[index].id + ']') ? 'has-error' : '']">
                                                <select class="control" :name="'quantity[' + orderItems[index].id + ']'"
                                                id="quantity" v-validate="validate[orderItems[index].id] || is_required ? 'required' : ''" data-vv-as="&quot;{{ __('rma::app.shop.default-option.select-quantity') }}&quot;">
                                                    <option :value="null">{{ __('rma::app.shop.default-option.select-quantity') }}</option>
                                                    <option v-for="qtyLength in quantity[orderData.id]"  :value="qtyLength">@{{ qtyLength }}</option>
                                                </select>
                                                <span class="control-error" v-if="errors.has('quantity[' + orderItems[index].id + ']')">@{{ errors.first('quantity[' + orderItems[index].id + ']') }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="control-group" :class="[errors.has('rma_reason_id[' + orderItems[index].id + ']') ? 'has-error' : '']">

                                                <select
                                                    class="control"
                                                    id="rma_reason_id"
                                                    :name=" 'rma_reason_id[' + orderItems[index].id + ']'"
                                                    v-validate="validate[orderItems[index].id] || is_required ? 'required' : ''"
                                                    data-vv-as="&quot;{{ __('rma::app.shop.default-option.select-reason') }}&quot;">

                                                    <option :value="null" >{{ __('rma::app.shop.default-option.select-reason') }}</option>
                                                    @foreach($reasons as $reasons_value)
                                                        <option value="{{ $reasons_value->id }}">
                                                            {{ $reasons_value->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="control-error" v-if="errors.has('rma_reason_id[' + orderItems[index].id + ']')">@{{ errors.first('rma_reason_id[' + orderItems[index].id + ']') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-if="resolutionShow == true">
                                <div v-if="orderItems.length == 0" style="text-align: center;">
                                    <p>{{ __('rma::app.shop.customer-rma-create.rma-not-avilable-quotes') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('option-wrapper', {
            template: '#options-template',

            inject: ['$validator'],

            data: function(data) {
                return {
                    data: [],
                    html: [],
                    child: [],
                    selected: [],
                    seller: false,
                    orderId: null,
                    quantity: [],
                    blankData: [],
                    sellerInfo: [],
                    seller_id: null,
                    resolution: null,
                    productImage: [],
                    orderStatus: null,
                    isCheckAll: false,
                    rmaOrderItemId: [],
                    is_required: false,
                    showSelectBox: false,
                    orderItems : [],
                    resolutions: [],
                    resolutionShow: false,
                    renderComponent: true,
                    countRmaOrderItems: [],
                    productImageCounts: null,
                    singleOrderSellerId: null,
                    orders: @json($orderItems),
                    validate: {
                        'is_required': false,
                    },
                }
            },

            methods: {
                getResolutions: function (order_id) {
                    var orderId = event.target.value;

                    this.resolutionShow = false;

                    if (orderId.length == 0 ) {
                        this.orderItems = this.blankData;
                        this.showSelectBox = false;
                        this.orderStatus = null;
                        this.resolutions = [];
                    }

                    this.$http.get(`{{ route('rma.customers.getproduct') }}/${orderId}`)
                    .then(response => {
                        this.data = response.data;

                        this.resolutions = response.data.resolutions;

                        this.orderId = this.data.orderId;
                        this.sellerInfo = this.data.sellerDetails;

                        this.renderComponent = true;
                        this.orderId = this.data.orderId;
                        this.orderStatus = ['Not Delivered'];

                        this.$forceUpdate();
                    }).catch(error => {
                        this.output = error;
                    });
                },

                getOrderByResolution: function (event) {
                    var orderId = this.orderId;
                    var resolution = event.target.value;

                    this.resolutionShow = true;

                    if (resolution == '') {
                        this.resolutionShow = false;
                        resolution = null;
                        this.orderItems = this.blankData;
                    }

                    this.$http.get(`{{ route('rma.customers.getproduct') }}/${orderId}/${resolution}`)
                    .then(response => {
                        this.orderItems = response.data.orderItems;

                        this.html = response.data.html;
                        this.child = response.data.child;
                        this.itemsId = response.data.itemsId;
                        this.quantity = response.data.quantity;
                        this.resolutions= response.data.resolutions;
                        this.orderStatus = response.data.orderStatus;
                        this.productImage = response.data.productImage;
                        this.rmaOrderItemId = response.data.rmaOrderItemId;
                        this.productImageCounts = response.data.productImageCounts;
                        this.countRmaOrderItems = response.data.countRmaOrderItems;

                        window.updateHeight();
                    }).catch(error => {
                        this.output = error;
                    });

                    this.renderComponent = true;
                    this.orderId = this.data.orderId;

                    this.$forceUpdate();

                    this.orderItems = this.blankData;
                },

                getOrderDetail: function (marketplace_seller_id) {
                        var marketplace_seller_id =  event.target.value;

                        if (marketplace_seller_id != '' ) {
                            this.resolutions = this.blankData;
                            this.seller = false;
                            this.resolutionShow = true;
                        } else {
                            this.orderItems = this.blankData;
                        }

                        var order_id = this.data['orderId'];

                        this.$http.post("{{ route('rma.customers.getproductbyseller') }}", {
                            marketplace_seller_id: marketplace_seller_id,
                            order_id: order_id,
                            resolution:this.resolutions
                        }).then(response => {
                            this.orderStatus = response.data.orderStatus;

                            this.resolutions= response.data.resolutions;
                        }).catch(error => {
                            this.output = error;
                        });
                },

                getId: function(e) {
                    this.validate[e.target.value] = e.target.checked ? true : false;
                },

                checkAll: function(){
                    this.selected = [];
                    this.isCheckAll = !this.isCheckAll;

                    if (this.isCheckAll) {
                        for (var key in this.orderItems) {
                            this.selected.push(this.orderItems[key].id);
                            this.validate[this.orderItems[key].id] = true;
                        }
                    }

                    if (!this.isCheckAll) {
                        for (var key in this.orderItems) {
                            this.validate[this.orderItems[key].id] = false;
                        }
                    }
                },

                updateCheckall: function(){
                    this.isCheckAll = this.selected.length == this.orderItems.length;
                },
            },
        });

        function  formValidation() {
            var allCheckbox = document.getElementById('checkbox').checked;
            var checkboxes = document.querySelectorAll('#checkboxSingle:checked'), values = [];
            Array.prototype.forEach.call(checkboxes, function(el) {
                values.push(el.value);
            });
            if (values.length > 0) {
                singleCheckBox = true;
            } else {
                singleCheckBox = false;
            }
            if (allCheckbox == false && singleCheckBox == false) {
                alert('Please select item');
                event.preventDefault();
                return false;
            }
        }

        $(document).ready(function() {
            $('#information').keypress(function(e) {
                max = 50;
                if (e.which < 0x20) {
                    return;
                }
                if (this.value.length == max) {
                    e.preventDefault();
                } else if (this.value.length > max) {
                    this.value = this.value.substring(0, max);
                }
            });

            $('.image-wrapper~.btn').click(() => {
                window.updateHeight();
            });
        })
    </script>
@endpush

@push('css')
    <style>
        .small-padding {
            padding: 3px 12px!important;
        }
        .image-wrapper {
            margin-bottom: 20px;
            margin-top: 10px;
            display: inline-block;
            width: 100%;
        }
        .image-wrapper .image-item {
            width: 200px;
            height: 200px;
            margin-right: 20px;
            background: #f8f9fa;
            border-radius: 3px;
            display: inline-block;
            position: relative;
            background-repeat: no-repeat;
            background-position: 50%;
            margin-bottom: 20px;
            float: left;
            background-image: url(/vendor/webkul/ui/assets/images/placeholder-icon.svg);
        }
        .image-wrapper .image-item .remove-image {
            background-image: linear-gradient(-180deg,rgba(0,0,0,.08),rgba(0,0,0,.24));
            border-radius: 0 0 4px 4px;
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px;
            text-align: center;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,.24);
            margin-right: 20px;
            cursor: pointer;
        }
        .image-wrapper .image-item input {
            display: none;
        }
        textarea:active,
        textarea:focus {
            border-color: #FF881A;
        }
        input[type=checkbox] {
            margin: 0px;
            width: 20px !important;
        }
    </style>
@endpush
