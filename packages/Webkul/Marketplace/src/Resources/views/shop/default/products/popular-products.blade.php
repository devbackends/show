<?php $products = app('Webkul\Product\Repositories\ProductFlatRepository')->getPopularProducts($seller->id) ?>

@if ($products->count())
    <section class="product-items section">

        <div class="section-heading">
            <h2>
                {{ __('marketplace::app.shop.products.popular-products') }}<br/>

                <span class="seperator"></span>
            </h2>

            <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="btn btn-lg btn-primary">{{ __('marketplace::app.shop.products.all-products') }}</a>
        </div>

        <div class="section-content product-grid-4">

            @foreach ($products as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat->product])

            @endforeach

        </div>

    </section>
@endif