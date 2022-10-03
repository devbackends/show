@extends('shop::layouts.master')

@section('seo')

    <meta name="description" content="2A Gun Show is the world's largest online marketplace site for shooting accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more!"/>
    <meta name="keywords" content="Gun shows, Gun sales, Selling Firearms, Buying Firearms, Firearms instructors, Firearm instructors near me, gun ranges, gun ranges near me, FFLs, FFLs near me, Gun clubs, Gun Leagues, Rifle Club, Hunting Clubs, USCA Membership, Firearms communities, Ammo, Firearms Accessories, Gun cases, bags & packs, Gun cleaning kits, Gun Magazines, Reloading supplies, gun grips, gun pads, gun stocks, gun bipods, gun storage, optics & sights, gun safety, gun protections, rifle range targets, Airguns, knives, survival gear, non-lethal defense, Barrels and choke tubes"/>

    <meta property="og:title" content="Home Page" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.2agunshow.com/" />
    <meta property="og:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="og:description" content="2A Gun Show is the world's largest online marketplace site for shooting accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more!" />

    <meta property="twitter:title" content="Home Page" />
    <meta property="twitter:type" content="website" />
    <meta property="twitter:url" content="https://www.2agunshow.com/" />
    <meta property="twitter:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="twitter:description" content="2A Gun Show is the world's largest online marketplace site for shooting accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more!" />

    <!-- SlickSlider -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <!-- END SlickSlider -->
@stop

@section('content-wrapper')

{{-- @include('shop::home.promo-grid') --}}
    @include('shop::home.promo-categories')
    @include('shop::home.promo-full-width-cta')
    @include('shop::home.other-categories')
    @include('shop::home.recent-products')
    @include('shop::home.new-sellers-slider')

    @include('shop::home.featured-products')

    <!-- @include('shop::home.promo-marketing') -->

    <div class="home-community-section">
    {{-- @include('shop::home.community-links') --}}
    @include('shop::home.community-links-slider')
    </div>


    @include('shop::home.join-cta')

    @include('shop::home.uscca-link')

    <!-- PROMO SECTION -->
    <div class="promo-section promo-section-big" style="background-image: url(/themes/market/assets/images/bg-promo-section-5.jpg);">
        <div class="container">
            <div class="row">
                <div class="col col-lg-7">
                    <h2 class="h1 promo-section__title">Find Training and Classes</h2>
                    <a href="/marketplace/certified-firearm-instructors" class="btn btn-primary promo-section__link">Learn More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END PROMO SECTION -->

@endsection