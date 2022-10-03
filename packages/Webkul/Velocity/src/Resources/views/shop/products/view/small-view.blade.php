@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@php
    $productBaseImage = $productImageHelper->getProductBaseImage($product);
@endphp

<div class="">
    <a href="{{ route('shop.product.index', $product->url_key) }}">
        <img src="{{ $productBaseImage['medium_image_url'] }}" class="col-12" />
    </a>

    <a href="{{ route('shop.product.index', $product->url_key) }}">
        <h2 class="col-12 fw6 link-color">{{ $product->name }}</h2>
    </a>
</div>