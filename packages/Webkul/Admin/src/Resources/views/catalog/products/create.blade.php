@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.add-title') }}
@stop

@section('css')
    <style>
        .table td .label {
            margin-right: 10px;
        }

        .table td .label:last-child {
            margin-right: 0;
        }

        .table td .label .icon {
            vertical-align: middle;
            cursor: pointer;
        }
    </style>
@stop

@section('content')
    <div class="content">
        <form method="POST" action="" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link"
                           onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.catalog.products.add-title') }}
                    </h3>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        Save and edit product
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()

                <?php $familyId = request()->input('family') ?>

                {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.before') !!}

            {{--    <accordian :title="'{{ __('admin::app.catalog.products.general') }}'" :active="true">--}}
                    <div slot="body">

                        {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.controls.before') !!}

                        <div class="product-types">

                            <div id="simple-container" data-tab="simple" class="square" data-description="A simple product is a physical item with a single SKU. Simple products have a variety of pricing and of input controls which makes it possible to sell variations of the product. Simple products can be used in association with grouped, bundle, and configurable products.">

                                <div class="icon-container"><span id="simple-icon"
                                                                  class="icon simple-product-icon product-icon"></span>
                                </div>
                                <div><span id="simple-title" class="product-type">simple</span></div>
                                <span class="icon yellow-triangle-icon triangle-simple dnone"></span>
                            </div>

                            <div id="configurable-container" data-tab="configurable" class="square" data-description="A configurable product appears to be a single product with lists of options for each variation. However, each option represents a separate, simple product with a distinct SKU, which makes it possible to track inventory for each variation.">

                                <div class="icon-container"><span id="configurable-icon"
                                                                  class="icon configurable-product-icon product-icon"></span>
                                </div>
                                <div><span id="configurable-title" class="product-type">Configurable</span></div>
                                <span class="icon yellow-triangle-icon triangle-configurable dnone"></span>
                            </div>

                            <div id="grouped-container" data-tab="grouped" class="square" data-description="A grouped product presents multiple, standalone products as a group. You can offer variations of a single product, or group them for a promotion. The products can be purchased separately or as a group.">

                                <div class="icon-container"><span id="grouped-icon"
                                                                  class="icon grouped-product-icon product-icon"></span>
                                </div>
                                <div><span id="grouped-title" class="product-type">Grouped</span></div>
                                <span class="icon yellow-triangle-icon dnone triangle-grouped"></span>
                            </div>


                            <div id="bundle-container" data-tab="bundle" class="square" data-description="A bundle product lets customers “build their own” from an assortment of options. The bundle could be a gift basket, computer, or anything else that can be customized. Each item in the bundle is a separate, standalone product.">

                                <div class="icon-container"><span id="bundle-icon"
                                                                  class="icon bundle-product-icon product-icon"></span>
                                </div>
                                <div><span id="bundle-title" class="product-type">Bundle</span></div>
                                <span class="icon yellow-triangle-icon dnone  triangle-bundle"></span>
                            </div>

                            <div id="virtual-container" data-tab="virtual" class="square" data-description="Virtual products are not tangible products, and are typically used for products such as services, memberships, warranties, and subscriptions. Virtual products can be used in association with grouped and bundle products.">

                                <div class="icon-container"><span id="virtual-icon"
                                                                  class="icon virtual-product-icon product-icon"></span>
                                </div>
                                <div><span id="virtual-title" class="product-type">Virtual</span></div>
                                <span class="icon yellow-triangle-icon dnone triangle-virtual"></span>
                            </div>

                            <div id="downloadable-container" data-tab="downloadable" class="square" data-description="A digitally downloadable product consists of one or more files that are downloaded. The files can reside on your server or be provided as URLs to any other server.">

                                <div class="icon-container"><span id="downloadable-icon"
                                                                  class="icon downloadable-product-icon product-icon"></span>
                                </div>
                                <div><span id="downloadable-title" class="product-type">Downloadable</span></div>
                                <span class="icon yellow-triangle-icon dnone triangle-downloadable"></span>
                            </div>
                            <div id="booking-container" data-tab="booking" class="square" data-description="With booking products, customers can easily purchase and book everything online including Appointments, Events, Rental, Tables Booking.">
                                <div class="icon-container"><span id="booking-icon"
                                                                  class="icon booking-product-icon product-icon"></span>
                                </div>
                                <div><span id="booking-title" class="product-type">Booking</span></div>
                                <span class="icon yellow-triangle-icon dnone triangle-booking"></span>
                            </div>

                            <div class="product-types-description dnone" >
                                With booking products, customers can easily purchase and book everything online including Appointments, Events, Rental, Tables Booking.
                            </div>
                        </div>

                        <div class="control-group hide" :class="[errors.has('type') ? 'has-error' : '']">
                            <label for="type"
                                   class="required">{{ __('admin::app.catalog.products.product-type') }}</label>
                            <select class="control" v-validate="'required'" id="type" name="type"
                                    {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.product-type') }}&quot;">

                                @foreach($productTypes as $key => $productType)
                                    <option class="type-option" id="option-{{$key}}" value="{{ $key }}" {{ request()->input('type') == $productType['key'] ? 'selected' : '' }}>
                                        {{ $productType['name'] }}
                                    </option>
                                @endforeach

                            </select>

                            @if ($familyId)
                                <input type="hidden" name="type" value="{{ app('request')->input('type') }}"/>
                            @endif
                            <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                            <label for="attribute_family_id"
                                   class="required">{{ __('admin::app.catalog.products.familiy') }}</label>
                            <select class="control" v-validate="'required'" id="attribute_family_id"
                                    name="attribute_family_id"
                                    {{ $familyId ? 'disabled' : '' }} data-vv-as="&quot;{{ __('admin::app.catalog.products.familiy') }}&quot;">
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

                        <div class="control-group" :class="[errors.has('sku') ? 'has-error' : '']">
                            <label for="sku" class="required">{{ __('admin::app.catalog.products.sku') }}</label>
                            <input type="text" v-validate="{ required: true, regex: /^[aA-zZ0-9]+(?:-[aA-zZ0-9]+)*$/ }"
                                   class="control" id="sku" name="sku"
                                   value="{{ request()->input('sku') ?: old('sku') }}"
                                   data-vv-as="&quot;{{ __('admin::app.catalog.products.sku') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('sku')">@{{ errors.first('sku') }}</span>
                        </div>
                        @if(!$familyId)
                        <br/>
                        <div class="page-action">
                            <button type="submit" class="btn btn-primary">
                                Save and edit product
                            </button>
                        </div>
                        @endif

                        {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.controls.after') !!}

                    </div>
              {{--  </accordian>--}}

                {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.general.after') !!}

                @if ($familyId)

                    {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.configurable_attributes.before') !!}

{{--                    <accordian :title="'{{ __('admin::app.catalog.products.configurable-attributes') }}'"
                               :active="true">--}}
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.configurable_attributes.controls.before') !!}

                            <div class="table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin::app.catalog.products.attribute-header') }}</th>
                                        <th>{{ __('admin::app.catalog.products.attribute-option-header') }}</th>
                                        <th></th>
                                        <th style="visibility: hidden;">Selected {{ __('admin::app.catalog.products.attribute-option-header') }}</th>

                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($configurableFamily->configurable_attributes as $attribute)
                                        <tr>
                                            <td>
                                                {{ $attribute->admin_name }}
                                            </td>
                                            <td>
                                                <select id="select_{{$attribute->code}}" class="js-example-basic-multiple"  data-attributecode="{{$attribute->code}}"  multiple>
                                                    @php $j=0;  $selected='selected'; @endphp
                                                    @foreach ($attribute->options as $option)

                                                    <option  value="{{ $option->id }}"  @if($j < 2) selected @endif >
                                                        {{ $option->admin_name }}
                                                    </option>
                                                        @php $j++;  @endphp
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="actions">
                                                <i class="icon trash-icon"></i>
                                            </td>
                                            <td  style="visibility: hidden;" id="td_{{$attribute->code}}">
                                                @php $i=0;  @endphp
                                                @foreach ($attribute->options as $option)
                                                    @php if($i==2) continue; @endphp
                                                    <span class="label">
                                                            <input type="hidden"
                                                                   name="super_attributes[{{$attribute->code}}][]"
                                                                   value="{{ $option->id }}"/>
                                                        {{ $option->admin_name }}

                                                        <i class="icon cross-icon"></i>
                                                        </span>
                                                    @php $i++;  @endphp
                                                @endforeach
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.configurable_attributes.controls.after') !!}

                        </div>
                    {{--</accordian>--}}

                    <br/>
                    <div class="page-action">
                        <button type="submit" class="btn btn-primary">
                            Save and edit product
                        </button>
                    </div>
                    {!! view_render_event('bagisto.admin.catalog.product.create_form_accordian.configurable_attributes.after') !!}
                @endif

            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {

            const families = JSON.parse('@json($families)');

            let url = new URLSearchParams(location.search);
            if (url.has('type')) {
                let type = url.get('type');
                $(`#${type}-container`).addClass('black-bg');
                $(`#${type}-container .product-type`).addClass('white')
                $(`#${type}-container .product-icon`).addClass('active')
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
                        '<i class="icon cross-icon"></i>\n' +
                        '</span>');
                });
            });

            $('.label .cross-icon').on('click', function (e) {
                $(e.target).parent().remove();
            });

            $('.actions .trash-icon').on('click', function (e) {
                $(e.target).parents('tr').remove();
            });
            $(document).on('click', '.square', function (e) {
                const tab = $(this).data('tab');

                const productFamilySelect = $('#attribute_family_id');
                let html = `<option value></option>`;
                if (tab === 'booking') {
                    for (let family of families) {
                        if (family.code.toLowerCase() === 'event') {
                            html += `<option value="${family.id}">${family.name}</option>`;
                            break;
                        }

                    }
                } else {
                    for (let family of families) {
                        if (family.code.toLowerCase() === 'event') continue;
                        html += `<option value="${family.id}">${family.name}</option>`;
                    }
                }
                productFamilySelect.html(html)

                $('.square').removeClass('black-bg');
                $('.product-icon').removeClass('active');
                $('.product-type').removeClass('white');

                $(this).addClass('black-bg');
                $('#' + tab + '-icon').addClass('active');
                $('#' + tab + '-title').addClass('white');
                $(".type-option").removeAttr('selected');
                $("#option-"+tab).attr("selected","selected");
            });
            $(document).on('mouseover', '.square', function (e) {
                var tab=$(this).data('tab');
                var description=$(this).data('description');
                $('.product-types-description').removeClass('dnone');
                $('.product-types-description').html(description);

                $('.yellow-triangle-icon').addClass('dnone');
                $('.triangle-'+tab).removeClass('dnone');


            });
            $(document).on('mouseout', '.square', function (e) {

                $(".product-types-description").addClass('dnone');
                $('.yellow-triangle-icon').addClass('dnone');

            });
        });
    </script>
@endpush