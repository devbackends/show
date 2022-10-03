@extends('shop::layouts.master')

@section('seo')
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    
    <meta property="og:url" content="https://gleam.io/a1PZA/refer-a-friend"/>
    <meta property="og:title" content="Battle Arms Development Rifle Give-Away!">
    <meta property="og:image" content="https://d36eyd5j1kt1m6.cloudfront.net/user-assets/1751939/PMXui6OS6dk23DZA/image_1920.jpeg"/>
    <meta property="twitter:card" content="summary_large_image"/>
    <meta property="twitter:image:src" content="https://d36eyd5j1kt1m6.cloudfront.net/user-assets/1751939/PMXui6OS6dk23DZA/image_1920.jpeg"/>
    <meta property="fb:app_id" content="152351391599356"/>
    <meta property="og:description" content="You can unlock more entries by sharing the giveaway with more people! Consider adding more actions such as sharing on Facebook or Instagram!">
@stop

@section('content-wrapper')

    <!-- PROMO SECTION -->
    <div class="promo-section promo-section-big" style="background-image: url(/themes/market/assets/images/bg-promo-section-6.jpg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col col-lg-5">

                    <div class="gun-giveaway-wrapper">
                      <a class="e-widget no-button generic-loader" href="https://gleam.io/POBbl/springfield-hellcat-giveaway" rel="nofollow">Springfield Hellcat Giveaway</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PROMO SECTION -->

@endsection
@push('scripts')
  <script type="text/javascript" src="https://widget.gleamjs.io/e.js" async="true"></script>
@endpush
@push('css')
<style>
    .gun-giveaway-wrapper {
        min-height: 400px;
        background: rgba(0, 0, 0, 0.75);
        padding: 1rem;
    }
    .gun-giveaway-wrapper iframe {
        margin: 0 auto 0 !important;
    }
</style>
@endpush