@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.edit-title') }}
@endsection

@section('content')

    <div class="account-layout right m10">
        <?php $locale = app()->getLocale(); ?>
        <?php $channel = core()->getCurrentChannelCode(); ?>

        {!! view_render_event('marketplace.sellers.account.catalog.product.edit.before', ['product' => $product]) !!}

        <form method="POST" action="" enctype="multipart/form-data" @submit.prevent="onSubmit" class="account-table-content">

            <div class="account-head mb-10">

                <span class="account-heading">
                    {{ __('marketplace::app.shop.sellers.account.catalog.products.edit-title') }}
                </span>

                <div class="account-action">
                    <button type="submit" class="btn btn-lg theme-btn">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>

                <div class="horizontal-rule"></div>

            </div>

            <div class="account-table-content">

                @csrf()

                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="locale" value="{{ $locale }}">
                <input type="hidden" name="channel" value="{{ $channel }}">

                <?php
                    $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository');

                    $seller = $productFlatRepository->getSellerByProductId($product->id);

                    $productFlat = $productFlatRepository->findWhere(['product_id'=>$product->id]);
                ?>

                @foreach ($product->attribute_family->attribute_groups as $attributeGroup)

                    @if (count($attributeGroup->custom_attributes))

                        {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.before', ['product' => $product]) !!}

                        @if ($attributeGroup->name == "General")
                            <accordian :title="'{{ __($attributeGroup->name) }}'" :active="true">
                                <div slot="body">
                                    {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.before', ['product' => $product]) !!}

                                    @foreach ($attributeGroup->custom_attributes as $attribute)

                                        @if (! $product->super_attributes->contains($attribute))

                                            <?php
                                                $validations = [];
                                                $disabled = false;
                                                if ($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'])) {
                                                    if (! $attribute->is_required)
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

                                                if ($attribute->code == 'status' && !$productFlat->is_approved) {
                                                    echo '<input type="hidden" name="status" value="0">';
                                                    continue;
                                                }
                                            ?>

                                            @if (view()->exists($typeView = 'admin::catalog.products.field-types.' . $attribute->type))

                                                <div class="form-group {{ $attribute->type }}" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=mandatory required' : '' }}>
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

                                                    <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                                </div>

                                            @endif

                                        @endif

                                    @endforeach

                                    {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.after', ['product' => $product]) !!}

                                </div>
                            </accordian>
                        @else
                            <accordian :title="'{{ __($attributeGroup->name) }}'" :active="false">
                                <div slot="body">
                                    {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.before', ['product' => $product]) !!}

                                    @foreach ($attributeGroup->custom_attributes as $attribute)
                                        @if (! $product->super_attributes->contains($attribute))

                                            <?php
                                                $validations = [];
                                                $disabled = false;
                                                if ($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'])) {
                                                    if (! $attribute->is_required)
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

                                                if ($attribute->code == 'status' && !$productFlat->is_approved) {
                                                    echo '<input type="hidden" name="status" value="0">';
                                                    continue;
                                                }
                                            ?>

                                            @if (view()->exists($typeView = 'admin::catalog.products.field-types.' . $attribute->type))

                                                <div class="form-group {{ $attribute->type }}" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=mandatory required' : '' }}>
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

                                                    <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                                </div>

                                            @endif

                                        @endif

                                    @endforeach

                                    {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.controls.after', ['product' => $product]) !!}

                                </div>
                            </accordian>
                        @endif

                        {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.' . $attributeGroup->name . '.after', ['product' => $product]) !!}

                    @endif

                @endforeach

                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.inventories.before', ['product' => $product]) !!}

                @include ('marketplace::shop.sellers.account.catalog.products.accordians.inventories')

                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.inventories.after', ['product' => $product]) !!}


                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.images.before', ['product' => $product]) !!}

                @include ('marketplace::shop.sellers.account.catalog.products.accordians.images')

                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.images.after', ['product' => $product]) !!}


                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.categories.before', ['product' => $product]) !!}

                @include ('marketplace::shop.sellers.account.catalog.products.accordians.categories')

                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.categories.after', ['product' => $product]) !!}

                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.variations.before', ['product' => $product]) !!}

                <div>
                    @include ('marketplace::shop.sellers.account.catalog.products.accordians.variations')
                </div>

                {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.variations.after', ['product' => $product]) !!}

            </div>

        </form>

        {!! view_render_event('marketplace.sellers.account.catalog.product.edit.after', ['product' => $product]) !!}

    </div>

@stop

@push('scripts')

    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#description, textarea#short_description',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true
            });
        });
    </script>

@endpush