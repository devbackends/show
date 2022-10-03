@extends('marketplace::shop.layouts.master')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.products.title', ['shop_title' => $seller->shop_title]) }} - {{ $seller->name }}
@endsection

@section('content-wrapper')

    @inject ('productRepository', 'Webkul\MarketplaceSaaS\Repositories\ProductRepository')

    <div class="main">

        {!! view_render_event('marketplace.shop.sellers.products.index.before', ['seller' => $seller]) !!}

        <div class="profile-container" style="margin-bottom: 40px;">
            @include('marketplace::shop.sellers.top-profile')
        </div>

        <section class="category-container seller-products">

            <div class="category-container">

                @include ('shop::products.list.layered-navigation')

                <div class="category-block">

                    <?php $products = $productRepository->findAllBySeller($seller); ?>

                    @if ($products->count())

                        @include ('shop::products.list.toolbar')

                        @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                            <div class="product-grid-3">
                                @foreach ($products as $productFlat)

                                    @include ('shop::products.list.card', ['product' => $productFlat])

                                @endforeach
                            </div>
                        @else
                            <div class="product-list">
                                @foreach ($products as $productFlat)

                                    @include ('shop::products.list.card', ['product' => $productFlat])

                                @endforeach
                            </div>
                        @endif

                        {!! view_render_event('marketplace.shop.sellers.products.index.pagination.before') !!}

                        <div class="bottom-toolbar">
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                        {!! view_render_event('marketplace.shop.sellers.products.index.pagination.after') !!}

                    @else

                        <div class="product-list empty">
                            <h2>{{ __('shop::app.products.whoops') }}</h2>

                            <p>
                                {{ __('shop::app.products.empty') }}
                            </p>
                        </div>

                    @endif

                </div>
            </div>

        </section>

        {!! view_render_event('marketplace.shop.sellers.products.index.after', ['seller' => $seller]) !!}

    </div>

@endsection