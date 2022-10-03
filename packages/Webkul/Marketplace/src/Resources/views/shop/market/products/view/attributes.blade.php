@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

{!! view_render_event('bagisto.shop.products.view.attributes.before', ['product' => $product]) !!}
@php
    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp


@if ($customAttributeValues)
<div class="product-detail__description">
<!--     <div class="group">
        <h5>Product information & Specs</h5>
        <p>FRAME Black composite Checkered front strap - SLIDE Machined aluminum Gray anodized finish for
            dramatic two-tone look Brushed polished flats - BARREL Rust-resistant Target crown - ACTION
            Semi-automatic Single-action trigger - GRIPS Black composite - FEATURES Extended ambidextrous
            thumb safety Beavertail grip safety Commander hammer Detachable 10-round magazine Dovetailed
            Combat fiber optic sights ABS carry case included - Caliber 22 LR - Barrel Length 4 14 - Overall
            Length 7 12 - Weight 14 oz - Magazine Capacity 10 - Sight Radius 5 38 - Barrel Finish Matte Black
            - Receiver Finish Matte - Front Sight Fiber Optic - Rear Sight Combat Fiber Optic - Barrel
            Material Steel - Checkering Textured Grip Panels - Receiver Material Black Composite - Trigger
            Finish Matte Black - Trigger Guard Finish Matte Black - Magazine Type Single Stack - Trigger
            Material Alloy - Trigger Guard Material Composite - Case ABS</p>
    </div> -->

    <div class="group">
        <h5>Details</h5>
        <ul class="list-group list-group-flush">

            @foreach ($customAttributeValues as $attribute)
            @if($attribute['value'])
            <li class="list-group-item">
                @if ($attribute['label'])
                <span>{{ $attribute['label'] }}</span>
                @else
                <span>{{ $attribute['admin_name'] }}</span>
                @endif
                @if ($attribute['type'] == 'file' && $attribute['value'])
                <span>
                    <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                        <i class="icon sort-down-icon download"></i>
                    </a>
                </span>
                @elseif ($attribute['type'] == 'image' && $attribute['value'])
                <span>
                    <a href="{{ route('shop.product.file.download', [$product->product_id, $attribute['id']])}}">
                        <img src="{{ Storage::url($attribute['value']) }}" style="height: 20px; width: 20px;" />
                    </a>
                </span>
                @else
                <span id="attribute-value">{{ $attribute['value'] }}</span>
                @endif
            </li>

            @endif
            @endforeach
            <li class="list-group-item">
            <span>Sku</span>
            <span>{{$product->sku}}</span>
            </li>
        </ul>
    </div>
</div>

@endif

{!! view_render_event('bagisto.shop.products.view.attributes.after', ['product' => $product]) !!}