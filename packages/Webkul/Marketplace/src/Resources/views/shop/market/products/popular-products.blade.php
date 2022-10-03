<?php $products = app('Webkul\Product\Repositories\ProductFlatRepository')->getPopularProducts($seller->id) ?>
@if ($products->count())
    <section class="product-items section mt-5">
        <div class="row section-heading mb-4">
            <div class="col">
                <h2>
                    {{ __('marketplace::app.shop.products.popular-products') }}<br/>
                    <span class="seperator"></span>
                </h2>
            </div>
            <div class="col-auto"><!-- <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="btn btn-lg btn-outline-primary">{{ __('marketplace::app.shop.products.all-products') }}</a> --></div>
        </div>

        <div class="row">
            @foreach ($products as $productFlat)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item__card">
                @include ('shop::products.list.card', ['product' => $productFlat, 'seller' => $seller->id])
                </div>
            @endforeach
        </div>
    </section>
@endif