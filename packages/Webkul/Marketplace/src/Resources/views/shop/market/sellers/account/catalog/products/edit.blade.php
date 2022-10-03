@php $GLOBALS['groups']=[]; @endphp

@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.edit-title') }}
@endsection

@section('content')
    <product-creation></product-creation>
@stop

@push('scripts')

    <script type="text/x-template" id="edit-page-error-alert-template">
        <div v-if="someErrors.items.length > 0" class="alert alert-danger" role="alert">
            <div class="alert__icon"><i class="far fa-exclamation-circle"></i></div>
            Please Fill Out The Missing Data In The Required Fields Below
        </div>
    </script>

    <!-- Component for the Product creation menu -->
    <script type="text/x-template" id="product-creation">
        <div class="settings-page" data-spy="scroll" data-target="#create-product-menu" data-offset="0">
            <?php $locale = app()->getLocale(); ?>
            <?php $channel = core()->getCurrentChannelCode(); ?>

            {!! view_render_event('marketplace.sellers.account.catalog.product.edit.before', ['product' => $product]) !!}

            <form id="productCreationForm" ref="productCreationForm" method="POST" action="" enctype="multipart/form-data" @submit.prevent="onSubmit" class="">

                <div class="settings-page__header align-items-center">
                    <div class="settings-page__header-title">
                        <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.edit-title') }}</p>
                    </div>
                    <div class="settings-page__header-actions">
                        <a href="/marketplace/account/catalog/products/"
                           class="btn btn-outline-gray-dark mr-2">Cancel</a>
                    </div>
                </div>

                <edit-page-error-alert :some-errors="errors"></edit-page-error-alert>

                @csrf()

                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="locale" value="{{ $locale }}">
                <input type="hidden" name="channel" value="{{ $channel }}">

                <?php

                $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository');

                $seller = $productFlatRepository->getSellerByProductId($product->id);

                $productFlat = $productFlatRepository->findWhere(['product_id'=>$product->id])->first();

                ?>

                <div class="settings-page__body">


                    <div class="row align-items-stretch">
                        <div class="col-12 col-md-3 col-lg-2 d-none d-lg-flex">
                            <div class="create-product__menu">
                                <!-- Steps menu -->
                                <div class="create-product-menu">
                                    <div class="create-product-menu__item" v-for="(item, index) in steps" :key="index">
                                        <a href="#" class="create-product-menu__item-title"
                                           :class="item.isActive ? 'create-product-menu__item-title--active' : ''"
                                           v-text="item.title"></a>
                                        <div class="create-product-menu__item-summary" v-if="item.summary">
                                            <p><span>@if($product->type=='configurable') Variable  @else {{ ucwords($product->type)}} @endif</span> / <span>{{app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->find($product->attribute_family_id)->name }}</span>
                                            </p>
                                        </div>
                                        <nav id="create-product-menu">
                                            <ul class="nav flex-column">
                                                <li class="nav-item create-product-menu__subitem"
                                                    :class="true ? 'create-product-menu__subitem--completed' : ''"
                                                    v-for="(subitem, index) in item.subSteps" :key="index">
                                                    <a class="nav-link" :href="`#${subitem.id}`" v-text="subitem.title"></a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <!-- END Steps menu -->
                            </div>
                        </div>

                        <div class="col-12 col-lg">
                            @foreach ($product->attribute_family->attribute_groups as $attributeGroup)
                                @if(strtolower($attributeGroup->name)=='shipping')
                                    <input type="hidden"  v-if="shipping_type=='flat_rate'" value="0" name="weight">
                                    <input type="hidden"  v-if="shipping_type=='flat_rate'" value="0" name="weight_lbs">
                                    <input type="hidden"  v-if="shipping_type=='flat_rate'" value="0" name="width">
                                    <input type="hidden"  v-if="shipping_type=='flat_rate'" value="0" name="height">
                                    <input type="hidden"  v-if="shipping_type=='flat_rate'" value="0" name="depth">
                                @endif
                                @php $customAttributes = $product->getEditableAttributes($attributeGroup); @endphp
                                @continue( (strtolower($attributeGroup->name) == "pricing" && $product->type === 'configurable' && !$product->parent_id) || (strtolower($attributeGroup->name) == "pricing" && $product->type === 'booking' && app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->find($product->attribute_family_id)->code =='event' )  )
                                @continue($attributeGroup->name == "Shipping" && $product->type === 'virtual')
                                @continue($attributeGroup->name == "Shipping" && $product->type === 'booking')
                                @continue(strtolower($attributeGroup->name) == "inventory")


                                    {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.before', ['product' => $product]) !!}
                                    @php
                                        $custom_field_groups = [
                                            'dimensions' => [
                                                'weight_lbs',
                                                'weight',
                                                'depth',
                                                'width',
                                                'height',

                                            ],
                                        ];
                                        $rendered_custom_field_groups = [];
                                    @endphp
                                    @if (strtolower($attributeGroup->name) != "common")
                                        @if (strtolower($attributeGroup->name) != "attributes" && strtolower($attributeGroup->name) != "media")
                                        @php array_push($GLOBALS['groups'],['id'=> str_replace(' ', '_', strtolower($attributeGroup->name)).'_box','title'=>$attributeGroup->name]); @endphp
                                            <div class="create-product__box" id="{{str_replace(' ', '_', strtolower($attributeGroup->name)).'_box'}}">
                                                <span  class="create-product__box-spy-anchor"></span>
                                                <p class="create-product__box-title">{{ ucwords($attributeGroup->name)}}</p>
                                                <!-- <p class="create-product__box-subtitle">Main Details</p> -->
                                                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.before', ['product' => $product]) !!}

                                                <div class="row">
                                                    @if($attributeGroup->name == "Shipping")
                                                        <div class="col-md-12 mb-3">
                                                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" v-model="shipping_type" id="auto_calculated" value="auto_calculated" name="shipping_type"  class="custom-control-input"> <label for="auto_calculated" class="custom-control-label">Auto calculated</label></div>
                                                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" v-model="shipping_type" id="flat_rate"       value="flat_rate"       name="shipping_type"        class="custom-control-input"> <label for="flat_rate"       class="custom-control-label">Flat rate</label></div>
                                                        </div>
                                                    @endif
                                                    @php $shipping_counter=0; @endphp
                                                        @foreach ($attributeGroup->custom_attributes as $attribute)
                                                            @if (! $product->super_attributes->contains($attribute))
                                                                <?php
                                                                if ($attribute->code == 'guest_checkout' || $attribute->code == 'tax_category_id') {
                                                                    continue;
                                                                }
                                                                $validations = [];
                                                                $disabled = false;
                                                                if ($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'weight'])) {
                                                                    if (!$attribute->is_required)
                                                                        continue;

                                                                    $disabled = true;
                                                                } else {
                                                                    if ($attribute->is_required) {
                                                                        array_push($validations, 'required');
                                                                    }

                                                                    if ($attribute->type == 'price') {
                                                                        array_push($validations, 'decimal');
                                                                    }

                                                                    array_push($validations, $attribute->validation);
                                                                }
                                                                if($attribute->code=='short_description'){
                                                                    array_push($validations,'max:1500');
                                                                }
                                                                if($attribute->code=='description'){
                                                                    array_push($validations,'max:2500');
                                                                }
                                                                $validations = implode('|', array_filter($validations));

                                                                if ($attribute->code == 'status' && !$productFlat->is_seller_approved) {
                                                                    echo '<input type="hidden" name="status" value="0">';
                                                                    continue;
                                                                }

                                                                ?>

                                                            @if (view()->exists($typeView = 'shop::sellers.account.catalog.products.field-types.' . $attribute->type))
                                                                        @if($attribute->code !='unavailable_in_states' && $attribute->code !='ground_only' && $attribute->code !='adult_sig')
                                                                            <div
                                                                                @if($attribute->pivot->width) class="col-md-<?=$attribute->pivot->width;?>" @else class="col-md-12" @endif >
                                                                                <div class="form-group {{ $attribute->type }}"
                                                                                     @if(in_array($attribute->code,['weight_lbs','weight','width','height','depth','shipping_size'])) v-if="shipping_type=='auto_calculated'" @endif
                                                                                     @if(in_array($attribute->code,['shipping_price','shipping_price_additional','free_shipping'])) v-if="shipping_type=='flat_rate'" @endif
                                                                                :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                                                    @if($attribute->type=='text')
                                                                                        <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                                        <div class="input-group">

                                                                                            @include ($typeView)
                                                                                            @if($attribute->name=='lbs' || $attribute->name=='oz')
                                                                                                <div class="input-group-append"><span class="input-group-text">{{ucwords($attribute->name)}} </span></div>
                                                                                            @endif
                                                                                            @if($attribute->code=='depth' || $attribute->code=='height' || $attribute->code=='width')
                                                                                                <div class="input-group-append">
                                                                                                    <span class="input-group-text">In.</span>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>

                                                                                    @elseif($attribute->type=='price')
                                                                                        <label
                                                                                            for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-prepend">
                                                                                    <span id="productPrice"
                                                                                          class="input-group-text">$</span>
                                                                                            </div>
                                                                                            @include ($typeView)
                                                                                        </div>
                                                                                    @elseif($attribute->type=='boolean')
                                                                                        @include ($typeView)
                                                                                    @else
                                                                                        <label
                                                                                            for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}
                                                                                            @if($attribute->code=='meta_title') <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon" data-original-title="A meta title is an important part of website optimization, and it's distinct from the headline on the page itself."></i>  @endif
                                                                                            @if($attribute->code=='meta_keywords') <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon" data-original-title="Meta keywords are a specific type of meta tag that appear in the HTML code of a Web page and help tell search engines what the topic of the page is."></i> @endif
                                                                                            @if($attribute->code=='meta_description') <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon" data-original-title="A meta description is an HTML element that describes an summarizes the contents of your page for the benefits of users and search engines. "></i> @endif
                                                                                        </label>
                                                                                        <div class="input-group">
                                                                                            @include ($typeView)
                                                                                        </div>

                                                                                    @endif
                                                                                    <span class="control-error"
                                                                                          v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                                                                </div>

                                                                            </div>
                                                                        @else
                                                                            @if($shipping_counter==0)
                                                                                <div class="col-md-12">
                                                                                    <p class="create-product__box-subtitle">Restrictions
                                                                                        <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon" data-original-title="Restriction for shipping."></i>
                                                                                    </p>
                                                                                </div>
                                                                                @php $shipping_counter+=1; @endphp
                                                                            @endif
                                                                                <div
                                                                                    @if($attribute->pivot->width) class="col-md-<?=$attribute->pivot->width;?>" @else class="col-md-12" @endif >
                                                                                    <div class="form-group {{ $attribute->type }}"
                                                                                         @if(in_array($attribute->code,['weight_lbs','weight','width','height','depth','shipping_size'])) v-if="shipping_type=='auto_calculated'" @endif
                                                                                         @if(in_array($attribute->code,['shipping_price','shipping_price_additional','free_shipping'])) v-if="shipping_type=='flat_rate'" @endif
                                                                                    :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                                                        @if($attribute->type=='text')
                                                                                            <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                                            <div class="input-group">
                                                                                                @include ($typeView)
                                                                                            </div>
                                                                                        @elseif($attribute->type=='boolean')
                                                                                            @include ($typeView)
                                                                                        @else
                                                                                            <label
                                                                                                for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                                            <div class="input-group">
                                                                                                @include ($typeView)
                                                                                            </div>

                                                                                        @endif
                                                                                        <span class="control-error"
                                                                                              v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                                                                    </div>

                                                                                </div>
                                                                        @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            @if(strtolower($attributeGroup->name)=='media')
                                            @php array_push($GLOBALS['groups'],['id'=>strtolower($attributeGroup->name).'_box','title'=>$attributeGroup->name]); @endphp
                                            <!-- Form Media -->
                                                <div class="create-product__box" id="{{str_replace(' ', '_', strtolower($attributeGroup->name)).'_box'}}">
                                                    <p class="create-product__box-title">Media</p>
                                                    <span id="media_box" class="create-product__box-spy-anchor"></span>
                                                    <div class="control-group">
                                                    <image-wrapper sku="{{$product->sku}}"  :items='@json($product->images)'></image-wrapper>
                                                    </div>
                                                    <div class="form-group text mt-4">
                                                        <label for="video" class="">Video</label>
                                                        <div class="input-group">

                                                            @php
                                                            $video='';
                                                            if($product->videos()->get()->count() > 0){
                                                              $video=$product->videos()->get()[0]->path;}
                                                            @endphp
                                                            <input type="text" placeholder="" value="{{$video}}" id="video" name="video" data-vv-as="&quot;Video&quot;" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END Form Media -->
                                            @endif
                                            @if(strtolower($attributeGroup->name) == 'attributes')
                                                    @php array_push($GLOBALS['groups'],['id'=>strtolower($attributeGroup->name).'_box','title'=>$attributeGroup->name]); @endphp
                                                    <div class="create-product__box" id="{{str_replace(' ', '_', strtolower($attributeGroup->name)).'_box'}}">
                                                        <span id="{{str_replace(' ', '_', strtolower($attributeGroup->name))}}" class="create-product__box-spy-anchor"></span>
                                                        <p class="create-product__box-title">{{ ucwords($attributeGroup->name)}}</p>
                                                        <!-- <p class="create-product__box-subtitle">Main Details</p> -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="selected-attributes">Select
                                                                    Attributes</label>
                                                                <Select2MultipleControl
                                                                    v-model="checkSelectedAttributes"
                                                                    :options="dynamicGroup.custom_attributes"
                                                                    @change="myChangeEvent($event)"
                                                                    @select="mySelectEvent($event)"></Select2MultipleControl>
                                                            </div>

                                                        </div>
                                                        <div class="create-product__box-selected-attributes" v-if="selectedAttributes.length != 0">
                                                            <div class="row">
                                                                <div class="col-md-12 mt-3"
                                                                    v-for="(selectedAttribute,key) in selectedAttributes"
                                                                    :key="key">
                                                                    <dynamic-input :attribute="selectedAttributes[key]"
                                                                                :product="product"></dynamic-input>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <a v-on:click="suggest_attribute=true" href="javascript:;" class="create-product__add-button"><i class="far fa-plus"></i>Suggest new attribute</a>
                                                            </div>
                                                            <div v-if="suggest_attribute" class="col-12">
                                                                <div class="form-group textarea" :class="[errors.has(`suggested-attribute`) ? 'has-error' : '']">
                                                                    <label class="form-label-required">Describe Suggested Attribute</label>
                                                                    <div class="input-group">
                                                                        <textarea style="height: 70px;min-height: unset;" id="suggested-attribute" name="suggested-attribute" v-model="suggested_attribute" class="form-control" ></textarea>
                                                                    </div>
                                                                    <span class="control-error" v-if="errors.has('suggested-attribute')">@{{ errors.first('suggested-attribute') }}</span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <button type="button" v-on:click="suggestNewAttribute" id="" class="submit-button btn mb-3 btn-primary"><span class="">Suggest</span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        @endif

                                    @endif

                                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.after', ['product' => $product]) !!}

                            @endif
                        @endforeach

                        @if($product->type === 'booking')
                            @include('bookingproduct::shop.sellers.account.catalog.products.accordians.booking')
                        @endif
                        @include ('marketplace::shop.sellers.account.catalog.products.accordians.inventories')
                        @include ('marketplace::shop.sellers.account.catalog.products.accordians.categories')
                        @include ('marketplace::shop.sellers.account.catalog.products.accordians.variations')


                        </div>
                        <div class="col-lg-3">
                            <div class="create-product__form-side">
                                <!-- Form Side Settings -->
                                <div class="create-product__box" id="{{str_replace(' ', '_', strtolower($attributeGroup->name))}}_box">
                                    <div>
                                        <submit-button  text="{{ __('admin::app.catalog.products.save-btn-title') }}" cssClass="mb-3" :loading="isLoading"></submit-button>
                                    </div>

                                    @foreach ($product->attribute_family->attribute_groups as $attributeGroup)
                                        @php $customAttributes = $product->getEditableAttributes($attributeGroup); @endphp
                                        @if (count($attributeGroup->custom_attributes))
                                            @if (strtolower($attributeGroup->name) == "common")
                                                @foreach ($attributeGroup->custom_attributes as $attribute)
                                                    @if (! $product->super_attributes->contains($attribute))
                                                        <?php
                                                        if ($attribute->code == 'guest_checkout' || $attribute->code == 'tax_category_id') {
                                                            continue;
                                                        }
                                                        $validations = [];
                                                        $disabled = false;
                                                        if ($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'weight'])) {
                                                            if (!$attribute->is_required)
                                                                continue;

                                                            $disabled = true;
                                                        } else {
                                                            if ($attribute->is_required) {
                                                                array_push($validations, 'required');
                                                            }

                                                            if ($attribute->type == 'price') {
                                                                array_push($validations, 'decimal');
                                                            }

                                                            array_push($validations, $attribute->validation);
                                                        }

                                                        $validations = implode('|', array_filter($validations));

                                                        if ($attribute->code == 'status' && !$productFlat->is_seller_approved) {
                                                            echo '<input type="hidden" name="status" value="0">';
                                                            continue;
                                                        }
                                                        ?>

                                                        @if (view()->exists($typeView = 'shop::sellers.account.catalog.products.field-types.' . $attribute->type))

                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group {{ $attribute->type }}"
                                                                         :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                                        @if($attribute->code=='featured')
                                                                            @php
                                                                                $product['is_seller_featured']=$productFlat['is_seller_featured'];
                                                                                $attribute->name='is_seller_featured';
                                                                                $attribute->code='is_seller_featured';
                                                                            @endphp

                                                                        @endif
                                                                        @if($attribute->code=='new')
                                                                            @php
                                                                                $product['is_seller_new']=$productFlat['is_seller_new'];
                                                                                $attribute->name='is_seller_new';                                                                                 $attribute->name='is_seller_featured';
                                                                                $attribute->code='is_seller_new';

                                                                            @endphp
                                                                        @endif

                                                                        @if($attribute->type=='text')
                                                                            <label
                                                                                for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                            <div class="input-group">


                                                                                @include ($typeView)
                                                                                @if($attribute->name=='lbs' || $attribute->name=='oz')
                                                                                    <div class="input-group-append">
                                                                                        <span
                                                                                            class="input-group-text">{{ucwords($attribute->name)}} </span>
                                                                                    </div>
                                                                                @endif
                                                                                @if($attribute->name=='Depth' || $attribute->name=='Height' || $attribute->name=='Width')
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">In.</span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @elseif($attribute->type=='price')
                                                                            <label
                                                                                for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend"><span
                                                                                        id="productPrice"
                                                                                        class="input-group-text">$</span>
                                                                                </div>
                                                                                @include ($typeView)
                                                                            </div>
                                                                        @elseif($attribute->type=='boolean')
                                                                            @include ($typeView)
                                                                        @else
                                                                            <label
                                                                                for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=form-label-required' : '' }}>{{ $attribute->admin_name }}</label>
                                                                            <div class="input-group">
                                                                                @include ($typeView)
                                                                            </div>

                                                                        @endif
                                                                        <span class="control-error"
                                                                              v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </form>
        </div>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {

            $(document).on('keydown','#units_per_box',function(e){
                if(e.keyCode == 110 || e.keyCode == 190){
                    e.preventDefault();
                    return ;
                }

                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }



                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });


             //the below condition is to check if the product is already saved so we need to show the sku , if not we need to check the autogenerate radio button

            // Functions
            function changePreorderQuantityInputDisplay() {
                const preorderQuantityInput = $('#preorder_qty');
                if (preorderInput.is(':checked')) {
                    preorderQuantityInput.parent().parent().css('display', 'block');
                    $('#preorder_qty').css('width', '100%');
                } else {
                    preorderQuantityInput.val(0);
                    preorderQuantityInput.parent().parent().css('display', 'none');
                }
            }

            function conditionChange() {
                let usedConditionSelect = $('#used_condition');
                if (conditionSelect.children('option:selected').attr('data-used')) {
                    usedConditionSelect.parent().parent().css('display', 'block');
                    $('#used_condition').css('width', '100%');
                } else {
                    usedConditionSelect.val(null);
                    usedConditionSelect.parent().parent().css('display', 'none');
                }
            }

            // Preorder
            const preorderInput = $('#allow_preorder')
            changePreorderQuantityInputDisplay();
            preorderInput.change(function () {
                changePreorderQuantityInputDisplay();
            })

            const conditionSelect = $('#condition');
            conditionChange();
            conditionSelect.change(function () {
                conditionChange();
            })

            let short_description_editor;
            let description_editor;
            ClassicEditor
                .create(document.querySelector('#short_description'), {
                        toolbar: {
                            items: [
                                'heading',
                                '|',
                                'bold',
                                'italic',
                                'link',
                                'unlink',
                                '|',
                                'bulletedList',
                                'numberedList',
                                '|',
                                'insertTable',
                                '|',
                                'undo',
                                'redo'
                            ]
                        },
                        image: {
                            toolbar: [

                            ]
                        },
                        table: {

                        },
                        language: 'en'

                    }
                )
                .then(newEditor => {
                    short_description_editor = newEditor;
                })
                .catch(error => {
                    console.error(error.stack);
                });
            ClassicEditor
                .create(document.querySelector('#description'), {
                        toolbar: {
                            items: [
                                'heading',
                                '|',
                                'bold',
                                'italic',
                                'link',
                                'unlink',
                                '|',
                                'bulletedList',
                                'numberedList',
                                '|',
                                'insertTable',
                                '|',
                                'undo',
                                'redo'
                            ]
                        },
                        image: {
                            toolbar: [

                            ]
                        },
                        table: {

                        },
                        language: 'en'

                    }
                )
                .then(newEditor => {
                    description_editor = newEditor;
                })
                .catch(error => {
                    console.error(error.stack);
                });
            $(document).on('keyup', short_description_editor, function () {
                $('#short_description').val(short_description_editor.getData());
            });
            $(document).on('keyup', description_editor, function () {
                $('#description').val(description_editor.getData());
            });

            $('input[name$="special_price_from"]').prop("disabled", true);
            $('input[name$="special_price_to"]').prop("disabled", true);
            $(document).on('blur', '#special_price', function () {
                var special_price = $(this).val();
                if (special_price > 0) {
                    $('input[name$="special_price_from"]').removeAttr("disabled");
                    $('input[name$="special_price_to"]').removeAttr("disabled");
                } else {
                    $('input[name$="special_price_from"]').prop("disabled", true);
                    $('input[name$="special_price_to"]').prop("disabled", true);
                }
            });

            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);
                const settings_page_header = document.querySelector('.settings-page__header');
                const settings_page = document.querySelector('.settings-page');
                if (scrollPosition > 66) {
                    if (settings_page_header) {
                        settings_page_header.classList.add('settings-page__header--fixed');
                    }
                    if (settings_page) {
                        settings_page.classList.add('settings-page--fixed');
                    }
                } else {
                    if (settings_page_header) {
                        settings_page_header.classList.remove('settings-page__header--fixed');
                    }
                    if (settings_page) {
                        settings_page.classList.remove('settings-page--fixed');
                    }
                }
            });


            $('body').scrollspy({
                target: "#create-product-menu"
            });

        });
    </script>
    <script>
        Vue.component('edit-page-error-alert', {
            template: '#edit-page-error-alert-template',
            props: ['someErrors'],
        })
    </script>

    <script>

        Vue.component('product-creation', {
            data: () => ({
                product: @json($product),
                shipping_type: '<?php echo $productFlat->shipping_type; ?>',
                isLoading:false,
                steps: [
                    {
                        title: "Product Type",
                        isCompleted: true,
                        isActive: true,
                        hasError: false,
                        summary: {
                            type: "Variable",
                            family: "General"
                        },
                        subSteps: []
                    }, {
                        title: "Product Information",
                        isCompleted: false,
                        isActive: true,
                        hasError: false,
                        subSteps:
                             @json($GLOBALS['groups'])

                    }
                ],
                dynamicGroup: @json($dynamicGroup),
                selectedAttributes: [],
                suggest_attribute: false,
                suggested_attribute: ''
            }),

            template: '#product-creation',

            created() {

            },
            mounted() {
                //remove guest_checkout and tax_category_id
                if(typeof this.dynamicGroup.custom_attributes !='undefined' ){
                    for (let i = 0; i < this.dynamicGroup.custom_attributes.length; i++) {
                        if(this.dynamicGroup.custom_attributes[i].code=='guest_checkout' || this.dynamicGroup.custom_attributes[i].code=='tax_category_id'){
                            this.dynamicGroup.custom_attributes.splice(i,1);
                            i-=1;
                        }
                    }
                }

                //set selected attributes
                if(typeof this.dynamicGroup.custom_attributes !='undefined' ){
                    for (let i = 0; i < this.dynamicGroup.custom_attributes.length; i++) {
                        if (this.product.hasOwnProperty(this.dynamicGroup.custom_attributes[i].code)) {
                            if (this.product[this.dynamicGroup.custom_attributes[i].code]) {
                                this.selectedAttributes.push(this.dynamicGroup.custom_attributes[i]);
                            }
                        }
                    }
                }

                eventBus.$on('onEditProductFormSubmit', (text) => {
                    this.isLoading= text;
                });
                $('.custom-select').select2();
                @if(!$product->url_key)
                this.validateAutoGenerateSku();
                @endif
            },
            computed: {
                checkSelectedAttributes() {
                    let array = [];
                    for (let i = 0; i < this.selectedAttributes.length; i++) {
                        array.push(this.selectedAttributes[i].id)
                    }
                    return array;
                }
            },
            methods: {
                checkDynamicSection() {
                    const selectedAttributes = $('#selected-attributes').val();
                },
                myChangeEvent(val) {

                    for (let j = 0; j < this.dynamicGroup.custom_attributes.length; j++) {

                        if (val.includes(this.dynamicGroup.custom_attributes[j].id.toString())) {
                            // check if item is already in array
                            var isFound = this.selectedAttributes.map(function (item) {
                                return item.id;
                            }).indexOf(this.dynamicGroup.custom_attributes[j].id);
                            console.log(isFound);
                            if (isFound == -1) {
                                let selectedAttributes=this.dynamicGroup.custom_attributes[j];
                                 selectedAttributes.options=this.dynamicGroup.custom_attributes[j].options;
                                this.selectedAttributes.push(selectedAttributes);

                            }
                        } else {
                            // get index of object with id:37
                            var removeIndex = this.selectedAttributes.map(function (item) {
                                return item.id;
                            }).indexOf(this.dynamicGroup.custom_attributes[j].id);
                            // remove object
                            if (removeIndex > -1) {
                                this.selectedAttributes.splice(removeIndex, 1);
                            }

                        }
                    }


                },
                mySelectEvent({id, text}) {
                    console.log('')
                },
                 validateAutoGenerateSku: function() {
                     if ($('#auto_generate_sku').is(':checked')) {
                         $('#sku').val($('#auto_generated_sku').val());
                         this.errors.remove('sku');
                       } else {
                         $('#sku').val('');
                     }
                 },
                validateUrlKey(){
                    const url_key=$('#url_key').val();
                    const this_this=this;
                    if(url_key){
                        this.$http.get('/marketplace/account/catalog/products/validate-url-key',{ params: { 'url_key': url_key,'product_id': this.product.id}} )
                            .then(function(response) {
                               if(response.status==200){
                                   if(response.data.status=='failed'){

                                       this_this.errors.add({
                                           field: 'url_key',
                                           msg: response.data.message
                                       });
                                   }else{
                                       this_this.errors.remove('url_key');
                                   }
                               }
                            })
                            .catch(function (error) {});
                    }
                },
                suggestNewAttribute(){
                    if(this.suggested_attribute) {
                        var this_this = this;
                        this_this.$http.post("{{ route('marketplace.product.suggest-by-seller') }}", {'suggestion':this_this.suggested_attribute,'type':'attribute'})
                            .then(function(response) {
                                if(response.status==200){
                                    if(response.data.status=='success'){
                                    }else{

                                    }
                                }else{

                                }
                                this_this.suggest_attribute=false;
                            })
                            .catch(function (error) {
                                this_this.suggest_attribute=false;
                            })
                    }
                }

            }
        });

    </script>
    <!-- END Component for the Product creation menu -->

@endpush