@php
$dimensions_fields_code = [
    'weight_lbs',
    'weight',
    'depth',
    'width',
    'height',
];
$dimensions_fields = [];
foreach ($customAttributes as $field) {
    if (in_array($field->code, $dimensions_fields_code)) {
        $dimensions_fields[$field->code] = $field;
    }
}
@endphp
<div class="control-group dimensions-groups mb-5">
    <div class="row package-weight-group">
        <div class="col-12">
            <b class="dimensions-sec-title">{{__('admin::app.catalog.products.package-weight')}}</b>
        </div>
        {{--WEIGHT LBS--}}
        @if(isset($dimensions_fields['weight_lbs']))
            @php($attribute = $dimensions_fields['weight_lbs'])
            <div class="col-3 {{$attribute->code}}_field">
                <div class="row no-gutters">
                    <div class="col-8 input-col control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                        @include ('admin::catalog.products.field-types.' . $attribute->type)

                        <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">
                        @{{ errors.first('{!! $attribute->code !!}') }}
                    </span>
                    </div>
                    <div class="col-4 label-col align-self-center">
                        <label for="{{$attribute->code}}" {{ $attribute->is_required ? 'class=required' : '' }}>
                            {{ $attribute->admin_name }}
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
                    </div>
                </div>
            </div>
        @endif
        {{--WEIGHT--}}
        @if(isset($dimensions_fields['weight']))
            @php($attribute = $dimensions_fields['weight'])
            <div class="col-3 {{$attribute->code}}_field">
                <div class="row no-gutters">
                    <div class="col-8 input-col control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                        @include ('admin::catalog.products.field-types.' . $attribute->type)

                        <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">
                        @{{ errors.first('{!! $attribute->code !!}') }}
                    </span>
                    </div>
                    <div class="col-4 label-col align-self-center">
                        <label for="{{$attribute->code}}" {{ $attribute->is_required ? 'class=required' : '' }}>
                            {{ $attribute->admin_name }}
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
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row package-dimensions-group">
        <div class="col-12">
            <b class="dimensions-sec-title">{{__('admin::app.catalog.products.package-dimensions')}}</b>
        </div>
        {{--DEPTH--}}
        @if(isset($dimensions_fields['depth']))
            @php($attribute = $dimensions_fields['depth'])
            <div class="col-3 {{$attribute->code}}_field">
                <div class="row no-gutters">
                    <div class="col-8 input-col control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                        <input type="text"
                               v-validate="'{{$validations}}'"
                               class="form-control"
                               id="{{ $attribute->code }}"
                               name="{{ $attribute->code }}"
                               placeholder="{{__('admin::app.catalog.products.l')}}"
                               value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
                               {{ in_array($attribute->code, ['sku', 'url_key']) ? 'v-slugify' : '' }}
                               data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $attribute->code == 'name' ? 'v-slugify-target=\'url_key\'' : ''  }} />

                        <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">
                        @{{ errors.first('{!! $attribute->code !!}') }}
                    </span>
                    </div>
                    <div class="col-4 label-col align-self-center">
                        <span>{{__('admin::app.catalog.products.in-x')}}</span>
                    </div>
                </div>
            </div>
        @endif

        {{--WIDTH--}}
        @if(isset($dimensions_fields['width']))
            @php($attribute = $dimensions_fields['width'])
            <div class="col-3 {{$attribute->code}}_field">
                <div class="row no-gutters">
                    <div class="col-8 input-col control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                        <input type="text"
                               v-validate="'{{$validations}}'"
                               class="form-control"
                               id="{{ $attribute->code }}"
                               name="{{ $attribute->code }}"
                               placeholder="{{__('admin::app.catalog.products.w')}}"
                               value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
                               {{ in_array($attribute->code, ['sku', 'url_key']) ? 'v-slugify' : '' }}
                               data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $attribute->code == 'name' ? 'v-slugify-target=\'url_key\'' : ''  }} />

                        <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">
                            @{{ errors.first('{!! $attribute->code !!}') }}
                        </span>
                    </div>
                    <div class="col-4 label-col align-self-center">
                        <span>{{__('admin::app.catalog.products.in-x')}}</span>
                    </div>
                </div>
            </div>
        @endif

        {{--HEIGHT--}}
        @if(isset($dimensions_fields['height']))
            @php($attribute = $dimensions_fields['height'])
            <div class="col-3 {{$attribute->code}}_field">
                <div class="row no-gutters">
                    <div class="col-8 input-col control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                        <input type="text"
                               v-validate="'{{$validations}}'"
                               class="form-control"
                               id="{{ $attribute->code }}"
                               name="{{ $attribute->code }}"
                               placeholder="{{__('admin::app.catalog.products.h')}}"
                               value="{{ old($attribute->code) ?: $product[$attribute->code] }}"
                               {{ in_array($attribute->code, ['sku', 'url_key']) ? 'v-slugify' : '' }}
                               data-vv-as="&quot;{{ $attribute->admin_name }}&quot;" {{ $attribute->code == 'name' ? 'v-slugify-target=\'url_key\'' : ''  }} />

                        <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">
                        @{{ errors.first('{!! $attribute->code !!}') }}
                    </span>
                    </div>
                    <div class="col-4 label-col align-self-center">
                        <span>{{__('admin::app.catalog.products.in-x')}}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@section('css')
    <style>
        .dimensions-sec-title{
          display: block;
          margin-bottom: 10px;
        }
        .package-dimensions-group{
            margin-top: 20px;
        }
        .package-weight-group .row .input-col,.package-dimensions-group .row .input-col{
            padding-right: 15px;
        }
        .package-weight-group input, .package-dimensions-group input{
            height: 50px;
        }
        .w
    </style>
@endsection