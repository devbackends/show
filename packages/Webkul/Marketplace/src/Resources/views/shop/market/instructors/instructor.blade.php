@extends('marketplace::shop.layouts.master')

@section('page_title')
    Instructors
@stop

@section('seo')
    <meta name="description" content="Instructors"/>
    <meta name="keywords" content="Instructors"/>
@stop

@section('content-wrapper')

    <!-- MODAL CONTACT SELLER -->
    <div class="modal fade" id="contactForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                    <div class="modal-head">
                        <i class="fal fa-comment-alt-lines"></i>
                        <h3>Contact {{$instructorData['instructor']}}</h3>
                    </div>
                    <contact-form
                        :customer="{{auth()->guard('customer')->user() ?? 'false'}}"
                        url="{{route('marketplace.instructors.contact', $instructorData['id'])}}"
                    ></contact-form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CONTACT SELLER -->

    <!-- HERO SECTION -->
    <div class="hero-section" style="background-image: url(/themes/market/assets/images/hero_show.jpg);">
        <div class="container">
            <a href="{{route('marketplace.instructors.index')}}" class="hero-section__link"><i class="fal fa-angle-left"></i>Back to all Instructors</a>
            <h1 class="h1 hero-section__title">{{str_replace('MR ', '', $instructorData['instructor'])}}</h1>
        </div>
    </div>
    <!-- END HERO SECTION -->
    <div class="shows-detail">

        <div class="container">
            <div class="row mb-4 mb-md-5">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Description</label>
                        <p class="font-paragraph-big-bold">{{$instructorData['title']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Location</label>
                        <p>{{$instructorData['location']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Contact</label>
                        <p class="text-capitalize">{{str_replace('mr ', '', strtolower($instructorData['instructor']))}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <!-- <div class="form-group">
                        <label>Phone</label>
                        <p>{{$instructorData['contact_phone']}}</p>
                    </div> -->
                    <a href="#" class="action btn btn-outline-primary" data-toggle="modal" data-target="#contactForm">
                        <i class="far fa-comment-alt-lines mr-2"></i>Contact instructor
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Cost</label>
                        <p>${{$instructorData['cost']}}</p>
                    </div>
                    <div class="form-group">
                        <label>Dates</label>
                        @foreach($instructorData['dates'] as $date)
                            <p class="mb-0">{{$date}}</p>
                        @endforeach
                    </div>
                    <!-- <a href="/marketplace/start-selling">
                        <img src="/themes/market/assets/images/2a_2020_nov_promo_5.jpg" alt="" class="w-100">
                    </a> -->
                </div>
                <div class="col-12 col-md-9 mt-3 mt-md-0">
                    <iframe width="100%" height="100%" style="min-height: 360px;" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $instructorData['map_location']; ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection