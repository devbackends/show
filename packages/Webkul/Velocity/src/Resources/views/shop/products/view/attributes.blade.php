@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}
@php
    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

@push('css')
    <style>
        #attribute-value {
            padding-left: 10px;
        }
    </style>
@endpush

@if ($customAttributeValues)
    <accordian :title="'{{ __('shop::app.products.specification') }}'" :active="'{{ $active }}' == true ? true : false">
        <div slot="header">
            <h3 class="heading">
                {{ __('velocity::app.products.more-infomation') }}
            </h3>
            <i class="rango-arrow"></i>
        </div>

        <div slot="body">
            <table class="full-specifications">

                @foreach ($customAttributeValues as $attribute)
                    @if($attribute['value'])
                        <tr>
                            @if ($attribute['label'])
                                <td>{{ $attribute['label'] }}</td>
                            @else
                                <td>{{ $attribute['admin_name'] }}</td>
                            @endif

                            @if ($attribute['type'] == 'file' && $attribute['value'])
                                <td>
                                    <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                        <i class="icon sort-down-icon download"></i>
                                    </a>
                                </td>
                            @elseif ($attribute['type'] == 'image' && $attribute['value'])
                                <td>
                                    <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                                        <img src="{{ Storage::url($attribute['value']) }}"
                                             style="height: 20px; width: 20px;"/>
                                    </a>
                                </td>
                            @else
                                <td id="attribute-value">{{ $attribute['value'] }}</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </accordian>
@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}