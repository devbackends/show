@extends('marketplace::shop.layouts.master')

@section('page_title')
    Guns Show
@stop

@section('seo')
    <meta name="description" content="Show"/>
    <meta name="keywords" content="Show"/>
@stop

@section('content-wrapper')
    <!-- HERO SECTION -->

    <div class="hero-section" style="background-image: url(/themes/market/assets/images/hero_show.jpg);">
        <div class="container">
            <a href="{{ url('marketplace/gun-shows') }}" class="hero-section__link"><i class="fal fa-angle-left"></i>Back to all gun shows</a>
            <h1 class="h1 hero-section__title">{{$show->title}}</h1>
            <span class="hero-section__label"><i class="far fa-badge-check"></i> Verified</span>
        </div>
    </div>
    <!-- END HERO SECTION -->
    <div class="shows-detail">
        <div class="container">
            <div class="shows-detail__info">
                <i class="fal fa-exclamation-triangle"></i>
                <p>Due to the COVID-19 out break we are encouraging all of our users to verify that the gun shows listed in this site have not been cancelled by contacting the promoter directly.</p>
            </div>
            <div class="shows-detail__description">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-3 shows-detail__description-item">
                        <i class="far fa-calendar-alt"></i>
                        <h5>Date</h5>
                        <p>{{$show->dates}}</p>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 shows-detail__description-item">
                        <i class="far fa-clock"></i>
                        <h5>Time</h5>
                        @foreach($show->hours as $hour)
                            <p>{{$hour}}</p>
                        @endforeach

                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 shows-detail__description-item">
                        <i class="fal fa-dollar-sign"></i>
                        <h5>Admission</h5>
                        <p>{{$show->admission}}</p>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 shows-detail__description-item">
                        <i class="fal fa-map-marked-alt"></i>
                        <h5>Location</h5>
                        <p>{{$show->location}}</p>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 col-md-8 mb-4 mb-md-0">
                    <!-- <p>{{$show->title}}, {{$show->location}} , {{$show->dates}} </p> -->
                    <div>
                        <iframe width="100%" height="360" id="gmap_canvas"
                                src="https://maps.google.com/maps?q=<?php echo $show->map_location; ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <h5>Promoter</h5>
                    <p class="font-paragraph-big">{{$promoter->name}}</p>
                    <a href="{{ route('marketplace.shows.promoters.get', strtolower(implode('-',explode(' ', $promoter->name)))) }}" class="btn btn-outline-gray btn-sm">More about this promoter</a>

    <!-- PROMO SECTION -->

    <div class="promo-section p-4 mt-4" style="background-image: url(/themes/market/assets/images/bg-promo-section-6.jpg);">

            <div class="row justify-content-center">
                <div class="col">
                    <h3 class="h3 promo-section__title">Gun show vendor?<br>The 2A Gun Show is 24 Hours a Day!</h3>
                    <a href="/marketplace/start-selling" class="btn btn-primary promo-section__link">Start Selling Now</a>
                </div>
            </div>
    </div>
    <!-- END PROMO SECTION -->
                </div>

            </div>

            <h5>Please Confirm All Gun Shows</h5>
            <p>Shows are liable to change dates, times or possibly cancel without notice to 2A Gun Show. Make sure to check with the Gun Show Coordinator for accurate dates, times and information.</p>
        </div>
    </div>

    <!-- SHOWS LIST SECTION -->
    <div class="shows-list shows-list--more">
        <div class="container">
            <div class="shows-list__head">
                <h2>More shows from this promoter</h2>
            </div>


            <show-wrapper  :shows='@json($promoterShows->items())'></show-wrapper>

        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection