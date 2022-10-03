@extends('shop::layouts.master')

@section('seo')

    <meta name="description" content="2A Gun Show is the world's largest online marketplace site for shooting accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more!"/>
    <meta name="keywords" content="Gun shows, Gun sales, Selling Firearms, Buying Firearms, Firearms instructors, Firearm instructors near me, gun ranges, gun ranges near me, FFLs, FFLs near me, Gun clubs, Gun Leagues, Rifle Club, Hunting Clubs, USCA Membership, Firearms communities, Ammo, Firearms Accessories, Gun cases, bags & packs, Gun cleaning kits, Gun Magazines, Reloading supplies, gun grips, gun pads, gun stocks, gun bipods, gun storage, optics & sights, gun safety, gun protections, rifle range targets, Airguns, knives, survival gear, non-lethal defense, Barrels and choke tubes"/>

    <meta property="og:title" content="Buy all Page" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.2agunshow.com/buyall" />
    <meta property="og:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="og:description" content="Buyall accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more!" />

    <meta property="twitter:title" content="Buy all Page" />
    <meta property="twitter:type" content="website" />
    <meta property="twitter:url" content="https://www.2agunshow.com/buyall" />
    <meta property="twitter:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="twitter:description" content="Buyall accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more!" />

@stop


@section('content-wrapper')

    <buy-all></buy-all>


    <!-- PROMO SECTION -->
    <div class="promo-section promo-section-big" style="background-image: url(/themes/market/assets/images/bg-promo-section-6.jpg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col col-lg-5">
                    <h2 class="h1 promo-section__title">Start Selling Your Used Gear!</h2>
                    <a href="/marketplace/start-selling" class="btn btn-primary promo-section__link">Learn More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END PROMO SECTION -->

@endsection
