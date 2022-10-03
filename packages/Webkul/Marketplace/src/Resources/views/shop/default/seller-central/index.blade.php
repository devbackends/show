@extends('marketplace::shop.layouts.master')

@section('page_title')
    {{ __('marketplace::app.shop.marketplace.title') }}
@stop

@section('content-wrapper')

    <div class="main seller-central-container">

        @if (core()->getConfigData('marketplace.settings.landing_page.show_banner'))
            <div class="banner-container">

                @if (core()->getConfigData('marketplace.settings.landing_page.banner'))
                    <img class="banner" src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.banner')) }}"/>
                @else
                    <img class="banner" src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/banner-3.png"/>
                @endif

                <div class="banner-content">
                    <h1>
                        {{ core()->getConfigData('marketplace.settings.landing_page.page_title') ?? __('marketplace::app.shop.marketplace.title') }}
                    </h1>

                    @if ($bannerContent = core()->getConfigData('marketplace.settings.landing_page.banner_content'))
                        <p>
                            {!! $bannerContent !!}
                        </p>
                    @endif

                    <a href="{{ route('customer.register.index') }}" class="btn btn-black btn-lg">
                        {{ core()->getConfigData('marketplace.settings.landing_page.open_shop_button_label') ?? __('marketplace::app.shop.marketplace.open-shop-label') }}
                    </a>
                </div>

            </div>
        @endif

        @if (core()->getConfigData('marketplace.settings.landing_page.show_features'))
            <div class="feature-container">
                <div class="feature-heading">
                    <h2>{{ core()->getConfigData('marketplace.settings.landing_page.feature_heading') ?? __('marketplace::app.shop.marketplace.features') }}</h2>

                    <p>{{ core()->getConfigData('marketplace.settings.landing_page.feature_info') ?? __('marketplace::app.shop.marketplace.features-info') }}</p>
                </div>

                <ul class="feature-list">
                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_1') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_1'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_1')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_1') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_2') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_2'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_2')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_2') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_3') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_3'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_3')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_3') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_4') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_4'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_4')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_4') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_5') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_5'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_5')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_5') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_6') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_6'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_6')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_6') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_7') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_7'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_7')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_7') }}
                            </div>
                        </li>
                    @endif

                    @if (core()->getConfigData('marketplace.settings.landing_page.feature_icon_8') && core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_8'))
                        <li>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('marketplace.settings.landing_page.feature_icon_8')) }}"/>

                            <div class="feature-label">
                                {{ core()->getConfigData('marketplace.settings.landing_page.feature_icon_label_8') }}
                            </div>
                        </li>
                    @endif
                </ul>

            </div>
        @endif


        @if (core()->getConfigData('marketplace.settings.landing_page.show_popular_sellers'))
            <?php $popularSellers = app('Webkul\Marketplace\Repositories\SellerRepository')->getPopularSellers(); ?>

            <?php $productImageHelper = app('Webkul\Product\Helpers\ProductImage'); ?>

            @if ($popularSellers->count())
                <div class="popular-sellers-container">
                    <div class="popular-sellers-heading">
                        {{ __('marketplace::app.shop.marketplace.popular-sellers') }}
                    </div>

                    <div class="popular-sellers-list">

                        @foreach ($popularSellers as $seller)
                            <div class="popular-seller-item">

                                <div class="profile-information">

                                    <div class="profile-logo-block">
                                        @if ($logo = $seller->logo_url)
                                            <img src="{{ $logo }}" style="width: 100%; height: 100%;"/>
                                        @else
                                            <img src="{{ bagisto_asset('images/default-logo.svg') }}" />
                                        @endif
                                    </div>

                                    <div class="profile-information-block">

                                        <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="shop-title">{{ $seller->shop_title }}</a>

                                        @if ($seller->country)
                                            <label class="shop-address">
                                                {{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}
                                            </label>
                                        @endif

                                        <div class="social-links">
                                            @if ($seller->facebook)
                                                <a href="https://www.facebook.com/{{$seller->facebook}}" target="_blank">
                                                    <i class="icon social-icon mp-facebook-icon"></i>
                                                </a>
                                            @endif

                                            @if ($seller->twitter)
                                                <a href="https://www.twitter.com/{{$seller->twitter}}" target="_blank">
                                                    <i class="icon social-icon mp-twitter-icon"></i>
                                                </a>
                                            @endif

                                            @if ($seller->instagram)
                                                <a href="https://www.instagram.com/{{$seller->instagram}}" target="_blank"><i class="icon social-icon mp-instagram-icon"></i></a>
                                            @endif

                                            @if ($seller->pinterest)
                                                <a href="https://www.pinterest.com/{{$seller->pinterest}}" target="_blank"><i class="icon social-icon mp-pinterest-icon"></i></a>
                                            @endif

                                            @if ($seller->skype)
                                                <a href="https://www.skype.com/{{$seller->skype}}" target="_blank">
                                                    <i class="icon social-icon mp-skype-icon"></i>
                                                </a>
                                            @endif

                                            @if ($seller->linked_in)
                                                <a href="https://www.linkedin.com/{{$seller->linked_in}}" target="_blank">
                                                    <i class="icon social-icon mp-linked-in-icon"></i>
                                                </a>
                                            @endif

                                            @if ($seller->youtube)
                                                <a href="https://www.youtube.com/{{$seller->youtube}}" target="_blank">
                                                    <i class="icon social-icon mp-youtube-icon"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="btn btn-lg btn-primary">
                                            {{ __('marketplace::app.shop.sellers.profile.visit-store') }}
                                        </a>

                                    </div>

                                </div>

                                <?php $popularProducts = app('Webkul\Product\Repositories\ProductFlatRepository')->getPopularProducts($seller->id); ?>

                                <div class="seller-products">

                                    @foreach ($popularProducts as $sellerProduct)

                                        <?php $productBaseImage = $productImageHelper->getProductBaseImage($sellerProduct->product, 5) ?>

                                        <div class="seller-product-item">
                                            <a href="{{ route('shop.product.index', $sellerProduct->product->url_key) }}" title="{{ $sellerProduct->product->name }}">
                                                <img src="{{ $productBaseImage['medium_image_url'] }}" />
                                            </a>
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
        @endif

        <div class="about-marketplace-container">

            {!! core()->getConfigData('marketplace.settings.landing_page.about_marketplace') !!}

        </div>

        <div class="setup-step-container">
            <div class="setup-heading">
                <h2>{{ __('marketplace::app.shop.marketplace.setup-title') }}</h2>

                <p>{{ __('marketplace::app.shop.marketplace.setup-info') }}</p>
            </div>

            <ul class="setup-step-list">
                <li class="active">
                    <span class="circle">
                        1
                    </span>

                    {{ __('marketplace::app.shop.marketplace.setup-1') }}
                </li>

                <li>
                    <span class="circle">
                        2
                    </span>

                    {{ __('marketplace::app.shop.marketplace.setup-2') }}
                </li>

                <li>
                    <span class="circle">
                        3
                    </span>

                    {{ __('marketplace::app.shop.marketplace.setup-3') }}
                </li>

                <li>
                    <span class="circle">
                        4
                    </span>

                    {{ __('marketplace::app.shop.marketplace.setup-4') }}
                </li>

                <li>
                    <span class="circle">
                        5
                    </span>

                    {{ __('marketplace::app.shop.marketplace.setup-5') }}
                </li>
            </ul>

        </div>

        @if (core()->getConfigData('marketplace.settings.landing_page.show_open_shop_block'))
            <div class="open-shop-information-container">
                <p class="open-shop-information">
                    {{ core()->getConfigData('marketplace.settings.landing_page.open_shop_info') ?? __('marketplace::app.shop.marketplace.open-shop-info') }}
                </p>

                <a href="{{ route('customer.register.index') }}" class="btn btn-black btn-lg">
                    {{ __('marketplace::app.shop.marketplace.open-shop-label') }}
                </a>
            </div>
        @endif

    </div>

@endsection