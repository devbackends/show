@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.assing-title') }}
@endsection

@section('content')
    <div class="account-layout">

        <form method="POST" action="" enctype="multipart/form-data" @submit.prevent="onSubmit" class="account-table-content">

            <div class="account-head">
                <h1 class="h3">{{ __('marketplace::app.shop.sellers.account.catalog.products.assing-title') }}</h1>
                <div class="account-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('marketplace::app.shop.sellers.account.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            {!! view_render_event('marketplace.sellers.account.catalog.products.assign.before') !!}

            <div class="account-table-content">

                @csrf()

                <div class="product-information">
                    <div class="row mb-5">
                    <div class="col-md-4 product-image">
                        <img src="{{ $baseProduct->base_image_url ?: bagisto_asset('images/product/meduim-product-placeholder.png') }}"/>
                    </div>

                    <div class="col-md-8 product-details product-detail__info">
                        <div class="product-name">
                            <a href="{{ url()->to('/').'/products/'.$baseProduct->url_key }}" title="{{ $baseProduct->name }}">
                                <h3>{{ $baseProduct->name }}</h3>
                            </a>
                        </div>

                        @include ('shop::products.price', ['product' => $baseProduct])
                    </div>
                    </div>
                </div>


                <div class="accordion list-group list-group-flush list-group-accordion" id="marketplace-product-edit">

                <div class="wrap list-group-item">
                    <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#accordionItemGeneral" aria-expanded="false" aria-controls="accordionItemGeneral"><span>{{ __('marketplace::app.shop.sellers.account.catalog.products.general') }}</span> <i class="fal fa-angle-right"></i></a>
                    <div id="accordionItemGeneral" class="collapse">
                        <div class="inner">

                            <div class="form-group" :class="[errors.has('condition') ? 'has-error' : '']">
                                <label for="condition" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.catalog.products.product-condition') }}</label>

                                <select class="form-control" v-validate="'required'" id="condition" name="condition" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.catalog.products.product-condition') }}&quot;">
                                    <option value="new">{{ __('marketplace::app.shop.sellers.account.catalog.products.new') }}</option>
                                    <option data-used="true" value="used">Used</option>
                                    <option value="refurbished">Refurbished</option>
                                </select>
                                <span class="control-error" v-if="errors.has('condition')">@{{ errors.first('condition') }}</span>
                            </div>

                            <div class="form-group" :class="[errors.has('used_condition') ? 'has-error' : '']">
                                <label for="condition">Used Condition</label>
                                <select class="form-control" id="used_condition" name="used_condition" data-vv-as="&quot;Used Condition&quot;">
                                    <option value="like_new">Like New</option>
                                    <option value="very_good">Very Good</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                </select>
                                <span class="control-error" v-if="errors.has('used_condition')">@{{ errors.first('used_condition') }}</span>
                            </div>

                            <div class="form-group booleantest" :class="[errors.has('featured') ? 'has-error' : '']">
                                <label for="featured">
                                    Featured
                                </label>
                                <label class="switch">
                                    <input type="checkbox" class="control" id="featured" name="featured">
                                    <span class="slider round"></span>
                                </label>
                                <span class="control-error" v-if="errors.has('featured')">@{{ errors.first('featured') }}</span>
                            </div>

                            <div class="form-group" :class="[errors.has('price') ? 'has-error' : '']">
                                <label for="price" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.catalog.products.price') }}</label>
                                <input type="text" v-validate="'required'" class="form-control" id="price" name="price" value="{{ old('price') }}" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.catalog.products.price') }}&quot;" {{ $baseProduct->type == 'configurable' ? 'disabled' : '' }}/>
                                <span class="control-error" v-if="errors.has('price')">@{{ errors.first('price') }}</span>
                            </div>

                            <div class="form-group form-group" :class="[errors.has('description') ? 'has-error' : '']">
                                <label for="description" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.catalog.products.description') }}</label>
                                <textarea v-validate="'required'" class="form-control" id="description" name="description" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.catalog.products.description') }}&quot;">{{ old('description') }}</textarea>
                                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                            </div>
                        </div>
                    </div>
                </div>


                    <div class="wrap list-group-item">
                    <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#accordionItemImages" aria-expanded="false" aria-controls="accordionItemImages"><span>{{ __('marketplace::app.shop.sellers.account.catalog.products.images') }}</span> <i class="fal fa-angle-right"></i></a>
                    <div id="accordionItemImages" class="collapse">
                        <div class="inner">
                            <div class="form-group">
                            <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images"></image-wrapper>
                            </div>
                        </div>
                    </div>
                </div>


                    @if ($baseProduct->type != 'configurable')
                    <div class="wrap list-group-item">
                    <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#accordionItemInventories" aria-expanded="false" aria-controls="accordionItemInventories"><span>{{ __('marketplace::app.shop.sellers.account.catalog.products.inventory') }}</span> <i class="fal fa-angle-right"></i></a>
                    <div id="accordionItemInventories" class="collapse">
                        <div class="inner">
                        @foreach ($inventorySources as $inventorySource)

<div class="form-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
    <label>{{ $inventorySource->name }}</label>

    <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="form-control" value="{{ old('inventories[' . $inventorySource->id . ']') }}" data-vv-as="&quot;{{ $inventorySource->name }}&quot;"/>

    <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
</div>

@endforeach
                        </div>
                    </div>
                </div>

                    @endif

                    @if ($baseProduct->type == 'configurable')

                    <div class="wrap list-group-item">
                    <a class="list-group-accordion-btn" href="#" data-toggle="collapse" data-target="#accordionItemVariations" aria-expanded="false" aria-controls="accordionItemVariations"><span>{{ __('marketplace::app.shop.sellers.account.catalog.products.variations') }}</span> <i class="fal fa-angle-right"></i></a>
                    <div id="accordionItemVariations" class="collapse">
                        <div class="inner">
                        <variant-list></variant-list>
                        </div>
                    </div>
                </div>

                    @endif
                </div>
                {!! view_render_event('marketplace.sellers.account.catalog.products.assign.after') !!}
                </div>
        </form>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(() => {
            const conditionSelect = $('#condition');
            function conditionChange() {
                let usedConditionSelect = $('#used_condition');
                if (conditionSelect.children('option:selected').attr('data-used')) {
                    usedConditionSelect.parent().css('display', 'block');
                } else {
                    usedConditionSelect.val(null);
                    usedConditionSelect.parent().css('display', 'none');
                }
            }
            conditionChange();
            conditionSelect.change(function () {
                conditionChange();
            })
        })
    </script>
@endpush

@if ($baseProduct->type == 'configurable')
@push('scripts')
    @parent

    <script type="text/x-template" id="variant-list-template">
        <div class="table" style="margin-top: 20px; overflow-x: unset;">
            <table>

                <thead>
                    <tr>
                        <th class=""></th>

                        <th>{{ __('admin::app.catalog.products.name') }}</th>

                        <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>

                        @foreach ($baseProduct->super_attributes as $attribute)
                            <th class="{{ $attribute->code }}" style="width: 150px">{{ $attribute->admin_name }}</th>
                        @endforeach

                        <th class="price" style="width: 100px;">{{ __('admin::app.catalog.products.price') }}</th>
                    </tr>
                </thead>

                <tbody>

                    <variant-item v-for='(variant, index) in variants' :variant="variant" :key="index" :index="index"></variant-item>

                </tbody>

            </table>
        </div>
    </script>

    <script type="text/x-template" id="variant-item-template">
        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" :id="variant.id" name="selected_variants[]" :value="variant.id" v-model="selected_variants[variant.id]">
                    <label :for="variant.id" class="checkbox-view"></label>
                </span>
            </td>

            <td>
                @{{ variant.name }}
            </td>

            <td>
                <button style="width: 100%;" type="button" class="dropdown-btn dropdown-toggle" :disabled="!selected_variants[variant.id]">
                    @{{ totalQty }}
                    <i class="icon arrow-down-icon"></i>
                </button>

                <div class="dropdown-list">
                    <div class="dropdown-container">
                        <ul>
                            <li v-for='(inventorySource, index) in inventorySources'>
                                <div class="form-group" :class="[errors.has(variantInputName + '[inventories][' + inventorySource.id + ']') ? 'has-error' : '']">
                                    <label>@{{ inventorySource.name }}</label>
                                    <input type="text" v-validate="'numeric|min:0'" :name="[variantInputName + '[inventories][' + inventorySource.id + ']']" v-model="inventories[inventorySource.id]" class="form-control" v-on:keyup="updateTotalQty()" :data-vv-as="'&quot;' + inventorySource.name  + '&quot;'"/>
                                    <span class="control-error" v-if="errors.has(variantInputName + '[inventories][' + inventorySource.id + ']')">@{{ errors.first(variantInputName + '[inventories][' + inventorySource.id + ']') }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </td>

            <td v-for='(attribute, index) in superAttributes'>
                @{{ optionName(variant[attribute.code]) }}
            </td>

            <td>
                <div class="form-group" :class="[errors.has(variantInputName + '[price]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" :name="[variantInputName + '[price]']" class="form-control" data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;" value="0" :disabled="!selected_variants[variant.id]"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[price]')">@{{ errors.first(variantInputName + '[price]') }}</span>
                </div>
            </td>
        </tr>
    </script>

    <script>
        var super_attributes = @json(app('\Webkul\Marketplace\Repositories\MpProductRepository')->getSuperAttributes($baseProduct));
        var variants = @json($baseProduct->variants);

        Vue.component('variant-list', {

            template: '#variant-list-template',

            inject: ['$validator'],

            data: () => ({
                variants: variants,
                superAttributes: super_attributes
            })
        });

        Vue.component('variant-item', {

            template: '#variant-item-template',

            props: ['index', 'variant'],

            inject: ['$validator'],

            data: () => ({
                inventorySources: @json($inventorySources),
                inventories: {},
                totalQty: 0,
                superAttributes: super_attributes,
                selected_variants: {}
            }),

            computed: {
                variantInputName () {
                    return "variants[" + this.variant.id + "]";
                }
            },

            methods: {
                optionName (optionId) {
                    var optionName = '';

                    this.superAttributes.forEach (function(attribute) {
                        attribute.options.forEach (function(option) {
                            if (optionId == option.id) {
                                optionName = option.admin_name;
                            }
                        });
                    })

                    return optionName;
                },

                updateTotalQty () {
                    this.totalQty = 0;
                    for (var key in this.inventories) {
                        this.totalQty += parseInt(this.inventories[key]);
                    }
                }
            }

        });
    </script>
@endpush
@endif