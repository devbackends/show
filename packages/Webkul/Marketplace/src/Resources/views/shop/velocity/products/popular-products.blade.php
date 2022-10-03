<?php $products = app('Webkul\Product\Repositories\ProductFlatRepository')->getPopularProducts($seller->id) ?>
@if ($products->count())
    <section class="product-items section">

        <div class="section-heading">
            <h2 class="padding-15">
                {{ __('marketplace::app.shop.products.popular-products') }}<br/>

                <span class="seperator"></span>
            </h2>

            <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="btn btn-lg theme-btn mr15">{{ __('marketplace::app.shop.products.all-products') }}</a>
        </div>

        <div class="row col-12 remove-padding-margin">
            @foreach ($products as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat])

            @endforeach
        </div>
    </section>
@endif