@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>
        <?php $channel = request()->get('channel') ?: core()->getDefaultChannelCode(); ?>

        {!! view_render_event('bagisto.admin.catalog.product.edit.before', ['product' => $product]) !!}

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">

                <div class="page-title">
                    <h3>
                        <i class="icon angle-left-icon back-link"
                           onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.catalog.products.edit-title') }}
                    </h3>

                    <div class="control-group">
                        <select class="control" id="channel-switcher" name="channel">
                            @foreach (core()->getAllChannels() as $channelModel)

                                <option
                                    value="{{ $channelModel->code }}" {{ ($channelModel->code) == $channel ? 'selected' : '' }}>
                                    {{ $channelModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" name="locale">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option
                                    value="{{ $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            <edit-page-error-alert :some-errors="errors"></edit-page-error-alert>

            <div class="page-content">
                @csrf()

                <input name="_method" type="hidden" value="PUT">

                @foreach ($product->attribute_family->attribute_groups as $index => $attributeGroup)
                    @if($product->type=="booking" && $attributeGroup->name=="Shipping")
                    @php continue; @endphp
                    @endif
                    <?php $customAttributes = $product->getEditableAttributes($attributeGroup); ?>

                    @if (count($customAttributes))

                        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.before', ['product' => $product]) !!}

                        <accordian :title="'{{ __($attributeGroup->name) }}'"
                                   :active="{{$index == 0 ? 'true' : 'false'}}">
                            <div slot="body">
                                {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.before', ['product' => $product]) !!}
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
                                @foreach ($customAttributes as $order => $attribute)
                                    <?php
                                        if ($attribute->code == 'guest_checkout') {
                                            continue;
                                        }

                                        $validations = [];

                                        if ($attribute->is_required) {
                                            array_push($validations, 'required');
                                        }

                                        if ($attribute->type == 'price') {
                                            array_push($validations, 'decimal');
                                        }

                                        array_push($validations, $attribute->validation);

                                        $validations = implode('|', array_filter($validations));
                                    ?>
                                        @php
                                            $is_custom_field = false;
                                            foreach ($custom_field_groups as $group){
                                                if(in_array($attribute->code, $group)){
                                                    $is_custom_field = true;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        @if($is_custom_field)
                                            @foreach($custom_field_groups as $group => $custom_type)
                                                @if(in_array($attribute->code, $custom_type))
                                                    @if(!in_array($group, $rendered_custom_field_groups))
                                                        @include('admin::catalog.products.field-types.custom_field_types')
                                                        @php(array_push($rendered_custom_field_groups, $group))
                                                    @endif
                                                @endif
                                            @endforeach
                                        @elseif(view()->exists($typeView = 'admin::catalog.products.field-types.' . $attribute->type))

                                            <div class="control-group {{ $attribute->type }}test" style="{{($attribute->code == 'used_condition') ? 'display:none' : ''}}"
                                                 @if ($attribute->type == 'multiselect') :class="[errors.has('{{ $attribute->code }}[]') ? 'has-error' : '']"
                                                 @else :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']" @endif
                                                 style="{{ ($attribute->code == 'preorder_qty') ? 'display:none;' : '' }}">

                                                <label
                                                    for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=required' : '' }}>
                                                    {{ $attribute->admin_name }}

                                                    @if ($attribute->type == 'price')
                                                        <span class="currency-code">({{ core()->currencySymbol(core()->getBaseCurrencyCode()) }})</span>
                                                    @endif

                                                    <?php
                                                    $channel_locale = [];

                                                    if ($attribute->value_per_channel) {
                                                        array_push($channel_locale, $channel);
                                                    }

                                                    if ($attribute->value_per_locale) {
                                                        array_push($channel_locale, $locale);
                                                    }
                                                    ?>

                                                    @if (count($channel_locale))
                                                        <span class="locale">[{{ implode(' - ', $channel_locale) }}]</span>
                                                    @endif
                                                </label>
                                                @include ($typeView)

                                                <span class="control-error text-danger"
                                                      @if ($attribute->type == 'multiselect') v-if="errors.has('{{ $attribute->code }}[]')"
                                                      @else  v-if="errors.has('{{ $attribute->code }}')"  @endif>
                                                @if ($attribute->type == 'multiselect')
                                                        @{{ errors.first('{!! $attribute->code !!}[]') }}
                                                    @else
                                                        @{{ errors.first('{!! $attribute->code !!}') }}
                                                    @endif
                                            </span>
                                            </div>

                                        @endif
                                @endforeach

                                {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.after', ['product' => $product]) !!}
                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.after', ['product' => $product]) !!}

                    @endif

                @endforeach

                {!! view_render_event(
                  'bagisto.admin.catalog.product.edit_form_accordian.additional_views.before',
                   ['product' => $product])
                !!}

                @foreach ($product->getTypeInstance()->getAdditionalViews() as $view)

                    @include ($view)

                @endforeach

                {!! view_render_event(
                  'bagisto.admin.catalog.product.edit_form_accordian.additional_views.after',
                   ['product' => $product])
                !!}
            </div>

        </form>

        {!! view_render_event('bagisto.admin.catalog.product.edit.after', ['product' => $product]) !!}
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script type="text/x-template" id="edit-page-error-alert-template">
        <div v-if="someErrors.items.length > 0" class="alert alert-danger mt-2 mx-2" role="alert">
            Please Fill Out The Missing Data In The Required Fields Below
        </div>
    </script>
    <script>
        Vue.component('edit-page-error-alert', {
            template: '#edit-page-error-alert-template',
            props: ['someErrors'],
            inject: ['$validator'],
        })
    </script>

    <script>
        $(document).ready(function () {

            // Functions
            function changePreorderQuantityInputDisplay() {
                const preorderQuantityInput = $('#preorder_qty');
                if (preorderInput.is(':checked')) {
                    preorderQuantityInput.parent().css('display', 'block');
                } else {
                    preorderQuantityInput.val(0);
                    preorderQuantityInput.parent().css('display', 'none');
                }
            }

            // Preorder
            const preorderInput = $('#allow_preorder')
            changePreorderQuantityInputDisplay();
            preorderInput.change(function () {
                changePreorderQuantityInputDisplay();
            })


            $('.js-example-basic-multiple').select2();

            $('#channel-switcher, #locale-switcher').on('change', function (e) {
                $('#channel-switcher').val()
                var query = '?channel=' + $('#channel-switcher').val() + '&locale=' + $('#locale-switcher').val();

                window.location.href = "{{ route('admin.catalog.products.edit', $product->id)  }}" + query;
            })

            tinymce.init({
                selector: 'textarea#description, textarea#short_description',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true
            });

            $('#show_on_marketplace').click(function () {
                let elem = $('#marketplace-category-select');
                if ($(this).is(':checked')) {
                    elem.css('display', 'block');
                } else {
                    elem.css('display', 'none')
                }
            })

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

        });
    </script>
@endpush
