@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.create-title') }}
@endsection

@section('content')

    <div class="settings-page">
        <create-product-form></create-product-form>
    </div>

@endsection
@push('scripts')

    <script type="text/x-template" id="create-product-template">
        <form method="POST" action="" @submit.prevent="onSubmit" ref="createForm">
            <div class="settings-page__header align-items-center">
                <div class="settings-page__header-title">
                    <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.create-title') }}</p>
                </div>
                <div class="settings-page__header-actions">
                    <!-- <a href="/marketplace/account/catalog/products/" class="btn btn-outline-gray-dark mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        {{ __('marketplace::app.shop.sellers.account.catalog.products.create') }}
                    </button> -->
                </div>
            </div>

            {!! view_render_event('marketplace.sellers.account.catalog.product.create.before') !!}
            <div class="settings-page__body">
                @csrf()
                <?php $familyId = app('request')->input('family') ?>
                <?php $sku = app('request')->input('sku') ?>
                {!! view_render_event('marketplace.sellers.account.catalog.product.create_form_accordian.general.before') !!}
                <div  v-if="!isMobile()">
                    <div class="row align-items-stretch">
                        <div class="col-12 col-lg-auto d-none d-lg-flex">
                            <div class="create-product__menu">
                                <product-steps-menu></product-steps-menu>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="create-product__type">
                                <!-- This is the new Product type selection -->
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="productTypeSimple" name="type" class="custom-control-input" value="simple"  v-validate="'required'">
                                    <label class="custom-control-label" for="productTypeSimple">
                                        <div class="row">
                                            <div class="col-auto mr-0 create-product__type-icon">
                                                <i class="fal fa-tag"></i>
                                            </div>
                                            <div class="col create-product__type-content">
                                                <p class="create-product__type-title">{{ __('admin::app.catalog.products.simple') }}</p>
                                                <p class="create-product__type-text">A simple product is a physical item with a single SKU.</p>
                                            </div>
                                        </div>
                                        <div class="create-product__family-container create-product__family-container--top">
                                            <div class="create-product__family">
                                                <img src="/themes/market/assets/images/create-product-family-arrow.svg" alt="" class="create-product__family-arrow">
                                                <div class="" :class="[errors.has('attribute_family_id') ? 'has-error' : '']" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
                                                    @foreach ($families as $family)
                                                        @if(strtolower($family->code) !="event" and $family->code!="training" and $family->code!="rental" and $family->code!="default_event")
                                                        <div class="custom-control custom-radio create-product__family-item">
                                                            <input type="radio" id="attribute_family_id-simple-{{ $family->id }}" name="attribute_family_id" class="custom-control-input" value="{{ $family->id }}" v-validate="'required'">
                                                            <label class="custom-control-label" for="attribute_family_id-simple-{{ $family->id }}"><span>{{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}{{ $family->name }}<submit-button type="button" @clickEvent="submitClicked" text="Next" faIconRight="chevron-right" cssClass="ml-2" :loading="isLoading"></submit-button></span></label>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="productTypeConfigurable" name="type" class="custom-control-input" value="configurable"  v-validate="'required'">
                                    <label class="custom-control-label" for="productTypeConfigurable">
                                        <div class="row">
                                            <div class="col-auto mr-0 create-product__type-icon">
                                                <i class="fal fa-clipboard-list-check"></i>
                                            </div>
                                            <div class="col create-product__type-content">
                                                <p class="create-product__type-title">{{ __('admin::app.catalog.products.variable') }}</p>
                                                <p class="create-product__type-text">A single product with different variations. Each variation can have its own price, stock, image, and you can manage them differently.</p>
                                            </div>
                                        </div>
                                        <div class="create-product__family-container create-product__family-container--middle">
                                            <div class="create-product__family">
                                                <img src="/themes/market/assets/images/create-product-family-arrow.svg" alt="" class="create-product__family-arrow">
                                                <div class="" :class="[errors.has('attribute_family_id') ? 'has-error' : '']" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
                                                    @foreach ($families as $family)
                                                        @if(strtolower($family->code) !="event" and $family->code!="training" and $family->code!="rental" and $family->code!="default_event")
                                                        <div class="custom-control custom-radio create-product__family-item">
                                                            <input type="radio" id="attribute_family_id-config-{{ $family->id }}" name="attribute_family_id" class="custom-control-input" value="{{ $family->id }}" v-validate="'required'">
                                                            <label class="custom-control-label" for="attribute_family_id-config-{{ $family->id }}"><span>{{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}{{ $family->name }}<submit-button type="button" @clickEvent="submitClicked" text="Next" faIconRight="chevron-right" cssClass="ml-2" :loading="isLoading"></submit-button></span></label>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="productTypeBooking" name="type" class="custom-control-input" value="booking"  v-validate="'required'">
                                    <label class="custom-control-label" for="productTypeBooking">
                                        <div class="row">
                                            <div class="col-auto mr-0 create-product__type-icon">
                                                <i class="fal fa-calendar-check"></i>
                                            </div>
                                            <div class="col create-product__type-content">
                                                <p class="create-product__type-title">Event</p>
                                                <p class="create-product__type-text">With booking products, customers can easily purchase and register for events, classes, training, etc.</p>
                                            </div>
                                        </div>
                                        <div class="create-product__family-container create-product__family-container--bottom">
                                            <div class="create-product__family">
                                                <img src="/themes/market/assets/images/create-product-family-arrow.svg" alt="" class="create-product__family-arrow">
                                                <div class="" :class="[errors.has('attribute_family_id') ? 'has-error' : '']" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
                                                    @foreach ($families as $family)
                                                        @if($family->code=="default_event" or strtolower($family->code) =="event" or $family->code=="training" ){{-- or $family->code=="rental"--}}
                                                            <div class="custom-control custom-radio create-product__family-item">
                                                                <input type="radio" id="attribute_family_id-event-{{ $family->id }}" name="attribute_family_id" class="custom-control-input" value="{{ $family->id }}" v-validate="'required'">
                                                                <label class="custom-control-label" for="attribute_family_id-event-{{ $family->id }}"><div>{{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}{{ $family->name }}<submit-button type="button" @clickEvent="submitClicked" text="Next" faIconRight="chevron-right" cssClass="ml-2" :loading="isLoading"></submit-button></div></label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group mb-0" :class="[errors.has('type') ? 'has-error' : '']">
                                    <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                                </div>
                                <div class="form-group mb-0" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                                    <span class="control-error" v-if="errors.has('attribute_family_id')">@{{ errors.first('attribute_family_id') }}</span>
                                </div>


                                <!-- END This is the new Product type selection -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row create-product__options mb-5" v-if="isMobile()">
                    <div class="col">
                        <div class="custom-control custom-radio custom-control-inline" data-toggle="tooltip" data-placement="bottom" title="A simple product is a physical item with a single SKU." data-custom-class="create-product__tooltip" data-template='<div class="tooltip create-product__tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-trigger="hover">
                            <input type="radio" id="productTypeSimple" name="type" class="custom-control-input" value="simple" checked="checked">
                            <label class="custom-control-label custom-control-label__simple" for="productTypeSimple">{{ __('admin::app.catalog.products.simple') }}</label>
                        </div>
{{--                        <div class="custom-control custom-radio custom-control-inline" data-toggle="tooltip" data-placement="bottom" title="Virtual products are not tangible products and are typically used for products such as services, memberships, donations, etc." data-template='<div class="tooltip create-product__tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner">Hello</div></div>' data-trigger="hover">
                            <input type="radio" id="productTypeVirtual" name="type" class="custom-control-input" value="virtual">
                            <label class="custom-control-label custom-control-label__virtual" for="productTypeVirtual">{{ __('admin::app.catalog.products.virtual') }}</label>
                        </div>--}}
                        <div class="custom-control custom-radio custom-control-inline" data-toggle="tooltip" data-placement="bottom" title="With booking products, customers can easily purchase and register for events, classes, training, etc." data-template='<div class="tooltip create-product__tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner">Hello</div></div>' data-trigger="hover">
                            <input type="radio" id="productTypeBooking" name="type" class="custom-control-input" value="booking">
                            <label class="custom-control-label custom-control-label__booking" for="productTypeBooking">Booking</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline" data-toggle="tooltip" data-placement="bottom" title="A configurable product is a single product with lists of options for each variation. Each option represents a separate, simple product with a distinct SKU, which makes it possible to track inventory for each variation. For instance, a T-shirt that comes in multiple colors and sizes." data-template='<div class="tooltip create-product__tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner">Hello</div></div>' data-trigger="hover">
                            <input type="radio" id="productTypeConfigurable" name="type" class="custom-control-input" value="configurable">
                            <label class="custom-control-label custom-control-label__configurable" for="productTypeConfigurable">{{ __('admin::app.catalog.products.configurable') }}</label>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="isMobile()">
                    <div class="form-group d-block d-lg-none"   :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                        <label for="attribute_family_id" class="required mandatory">{{ __('admin::app.catalog.products.familiy') }}</label>
                        <select class="form-control" v-validate="'required'" id="attribute_family_id" name="attribute_family_id" {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
                            <option value=""></option>
                            @foreach ($families as $family)
                                <option value="{{ $family->id }}" {{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}>{{ $family->name }}</option>
                            @endforeach
                        </select>
                        @if ($familyId)
                            <input type="hidden" name="attribute_family_id" value="{{ $familyId }}"/>
                        @endif
                        <span class="control-error" v-if="errors.has('attribute_family_id')">@{{ errors.first('attribute_family_id') }}</span>
                    </div>

                </div>
                <div class="row" v-if="isMobile()">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ __('marketplace::app.shop.sellers.account.catalog.products.create') }}
                        </button>
                    </div>
                </div>
                {!! view_render_event('marketplace.sellers.account.catalog.product.create_form_accordian.general.after') !!}

            </div>
            {!! view_render_event('marketplace.sellers.account.catalog.product.create.after') !!}
        </form>
    </script>

    <script>
        Vue.component('create-product-form', {

            data: () => ({
                isLoading: false,
            }),

            template: '#create-product-template',

            created() {
            },

            methods: {
                setProductType(product_type){
                },
                submitClicked: function(){
                    event.preventDefault();
                    this.isLoading = true;
                    this.$refs.createForm.submit();
                }
            }
        });

    </script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            const families = JSON.parse('@json($families)');
            const selectedType = '{{request()->get('type')}}'
            if (selectedType && selectedType !== '') {
                $('input[name="type"][value="'+selectedType+'"]').trigger('click');
            }
            setAttributeFamilies();

            $('.custom-control-input').click(() => {
                setAttributeFamilies();
            });
            function setAttributeFamilies(){
                const productFamilySelect = $('#attribute_family_id');
                let html = `<option value=""></option>`;
                if ($('input[name="type"]:checked').val() === 'booking') {
                    for (let family of families) {
                        if (family.code.toLowerCase() === 'event' || family.code.toLowerCase() === 'training' || family.code.toLowerCase() === 'default_event' ) {
                            html += `<option value="${family.id}">${family.name}</option>`;
                        }

                    }
                } else {
                    for (let family of families) {
                        if (family.code.toLowerCase() === 'event' || family.code.toLowerCase() === 'training' || family.code.toLowerCase() === 'rental' || family.code.toLowerCase() === 'default_event') continue;
                        html += `<option value="${family.id}">${family.name}</option>`;
                    }
                }
                productFamilySelect.html(html);
            }
            $('.js-example-basic-multiple').select2();
            $(document).on('change','.js-example-basic-multiple',function (e){
                var attribute_code=$(this).data('attributecode');
                var sel = $("#select_"+attribute_code+" option:selected");
                $('#td_'+attribute_code).html(' ');
                $(sel).each(function() {
                    $('#td_'+attribute_code).append('<span class="label">\n' +
                        '<input type="hidden"\n' +
                        'name="super_attributes['+attribute_code+'][]"\n' +
                        'value="'+$(this).val()+'"/>\n' +
                        ''+$(this).text()+'\n' +
                        '\n' +
                        '</span>');
                });
            });
            $('.label .cross-icon').on('click', function(e) {
                $(e.target).parent().remove();
            })

            $('.actions .trash-icon').on('click', function(e) {
                $(e.target).parents('tr').remove();
            })

            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);
                const settingsPageHeader=document.querySelector('.settings-page__header');
                const settingsPage= document.querySelector('.settings-page');
                if (scrollPosition > 66){
                    if(settingsPageHeader){
                        settingsPageHeader.classList.add('settings-page__header--fixed');
                    }
                    if(settingsPage){
                        settingsPage.classList.add('settings-page--fixed');
                    }
                } else {
                    if(settingsPageHeader){
                        document.querySelector('.settings-page__header').classList.remove('settings-page__header--fixed');
                    }
                    if(settingsPage){
                        document.querySelector('.settings-page').classList.remove('settings-page--fixed');
                    }

                }
            });

        });
    </script>
@endpush