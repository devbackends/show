@extends('marketplace::shop.layouts.master')

@section('page_title')
    Promoter details
@stop

@section('seo')
    <meta name="description" content="Promoter"/>
    <meta name="keywords" content="Promoter"/>
@stop

@push('css')
    <style>
        .hero-section__title {
            margin-bottom: 0;
        }

        .hero-section__title-sub {
            color: rgba(255, 255, 255, 0.5);
            display: block;
        }
    </style>
@endpush

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
                        <h3>Contact {{$promoter->name}}</h3>
                    </div>
                    <contact-form
                        :customer="{{auth()->guard('customer')->user() ?? 'false'}}"
                        url="{{route('marketplace.shows.promoters.contact', $promoter->id)}}"
                    ></contact-form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CONTACT SELLER -->

    <!-- HERO SECTION -->
    <div class="hero-section" style="background-image: url(/themes/market/assets/images/hero_show.jpg);">
        <div class="container">
            <a href="{{ url('marketplace/gun-shows') }}" class="hero-section__link"><i class="fal fa-angle-left"></i>Back to Gun Show</a>
            <div>
                <h1 class="h1 hero-section__title">{{$promoter->name}}</h1>
                <h3 class="hero-section__title-sub">Promoter</h3>
            </div>
        </div>
    </div>

    <!-- END HERO SECTION -->
    <div class="shows-detail">
        <div class="container">
            <!-- <div class="shows-detail__info">
              <i class="fal fa-exclamation-triangle"></i>
              <p>Due to the COVID-19 out break we are encouraging all of our users to verify that the gun shows listed in
                this site have not been cancelled by contacting the promoter directly.</p>
            </div> -->
            <div class="row pt-md-2">
                <div class="col-12 col-md-4 shows-detail__description-item">
                    <i class="fal fa-user"></i>
                    <h5>Contact</h5>
                    <p><?php echo htmlspecialchars_decode($promoter->contact); ?></p>
                </div>
                <div class="col-12 col-md-4 shows-detail__description-item">
                    <i class="fal fa-phone"></i>
                    <h5>Phone</h5>
                    <p>{{$promoter->phone}}</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="#" class="action btn btn-outline-primary" data-toggle="modal" data-target="#contactForm">
                        <i class="far fa-comment-alt-lines mr-2"></i>Contact promoter
                    </a>
                </div>
            </div>
        </div>

        <!-- SHOWS LIST SECTION -->
        <div class="shows-list shows-list--promoter">
            <div class="container">
                <div class="shows-list__head">
                    <h3>Upcoming shows from this promoter</h3>
                </div>
                <show-wrapper  :shows='@json($promoterShows->items())'></show-wrapper>
            </div>
        </div>
        <!-- END SHOWS LIST SECTION -->
    </div>
    @include('shop::home.uscca-link')
@endsection