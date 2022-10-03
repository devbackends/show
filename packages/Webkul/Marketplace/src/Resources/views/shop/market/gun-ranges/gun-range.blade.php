@extends('marketplace::shop.layouts.master')

@section('page_title')
    Gun Range
@stop

@section('seo')
    <meta name="description" content="Gun Range"/>
    <meta name="keywords" content="Gun Range"/>
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
                        <h3>Contact {{$gunRangeData['name']}}</h3>
                    </div>
                    <contact-form
                        :customer="{{auth()->guard('customer')->user() ?? 'false'}}"
                        url="{{route('marketplace.gun-ranges.contact', $gunRangeData['id'])}}"
                    ></contact-form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CONTACT SELLER -->

    <!-- HERO SECTION -->
    <div class="hero-section" style="background-image: url(/themes/market/assets/images/hero_show.jpg);">
        <div class="container">
            <a href="{{route('marketplace.gun-ranges.index')}}" class="hero-section__link"><i class="fal fa-angle-left"></i>Back to all Gun Ranges</a>
            <h1 class="h1 hero-section__title">{{$gunRangeData['name']}}</h1>
        </div>
    </div>
    <!-- END HERO SECTION -->
    <div class="shows-detail">
        <div class="container">
            <div class="row mb-4 mb-md-0">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>Description</label>
                        <p class="font-paragraph-big-bold">{{$gunRangeData['facilities']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Address</label>
                        <p>{{$gunRangeData['location']}}</p>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <p>{{$gunRangeData['phone']}}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        @if($gunRangeData['email'])
                            <a href="#" class="action btn btn-outline-primary" data-toggle="modal" data-target="#contactForm"><i class="far fa-comment-alt-lines mr-2"></i>Contact</a>
                        @else
                            <p>-</p>
                        @endif
                    </div>
                    <div>
                        @if($gunRangeData['web'])
                            <a href="{{$gunRangeData['web']}}" target="_blank" class="btn btn-outline-primary"><i class="far fa-globe mr-2"></i>Visit website</a>
                        @else

                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label>Hours</label>
                        @if($gunRangeData['hours'])
                            <p>{{$gunRangeData['hours']}}</p>
                        @else
                            <br/>-
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Range Type</label>
                        <p>{{$gunRangeData['range_category']}}</p>
                    </div>
                    <div class="form-group">
                        <label>Range Access</label>
                        <p>{{$gunRangeData['range_access']}}</p>
                    </div>
                    <a href="/marketplace/start-selling">
                        <img src="/themes/market/assets/images/2a_2020_nov_promo_4.jpg" alt="" class="w-100">
                    </a>
                </div>
                <div class="col-12 col-md-9 mt-3 mt-md-0">
                    <iframe width="100%" height="100%" style="min-height: 360px;" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $gunRangeData['map_location']; ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection