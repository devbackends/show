@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@php
    $productBaseImage = $productImageHelper->getProductBaseImage($product);
@endphp

<div>
    <a href="{{ route('shop.product.index', $product->url_key) }}" class="d-block mb-4">
        <img src="{{ $productBaseImage['medium_image_url'] }}" class="w-100" />
    </a>

<!--     <a href="{{ route('shop.product.index', $product->url_key) }}">
        <h3>{{ $product->name }}</h3>
    </a> -->
</div>