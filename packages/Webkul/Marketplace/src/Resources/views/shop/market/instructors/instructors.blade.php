@extends('marketplace::shop.layouts.master')

@section('page_title')
Instructors
@stop

@section('seo')
<meta name="description" content="Instructors" />
<meta name="keywords" content="Instructors" />
@stop

@section('content-wrapper')
<div>
    <div class="instructors-hero" id="form">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="instructors-hero__content">
                        <h2>Welcome to 2a academy</h2>
                        <p class="text-primary">Powered by 2A Gun Show</p>
                        <p class="font-paragraph-big-bold">The fasting growing shooting
                            sports website on the internet</p>
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>
    <div class="instructors-form mb-5" id="m-form">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="instructors-form__screen">
                        <img src="/themes/market/assets/images/academy-screenshot.png" alt="">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="instructors-form__form-wrapper">
                        @include('shop::instructors.instructor-signup-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="instructors-promote mb-5">
        <div class="container pb-5">
            <h2 class="text-info-dark text-center mb-5">promote your classes with 2a Academy!</h2>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="instructors-promote__card">
                        <div class="instructors-promote__card-icon">
                            <i class="far fa-bullseye"></i>
                        </div>
                        <p class="font-paragraph-big-bold">Student Registration</p>
                        <p>Register students for classes and events directly through your 2A Academy webstore.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="instructors-promote__card">
                        <div class="instructors-promote__card-icon">
                            <i class="far fa-store"></i>
                        </div>
                        <p class="font-paragraph-big-bold">Free Webstore</p>
                        <p>Use our free webstore to display a bit about yourself, your credentials, your events, as well as any products you may have for sale.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="instructors-promote__card">
                        <div class="instructors-promote__card-icon">
                            <i class="far fa-calendar-check"></i>
                        </div>
                        <p class="font-paragraph-big-bold">Class Management</p>
                        <p>Our powerful tools make it easy to manage your customers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="instructors-credentials mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <div class="instructors-credentials__badges">
                        <img src="/themes/market/assets/images/profile-badge-shooter.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-uscca.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-ffl.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-nra.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-veteran.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-retailer.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-promoter.png" alt="">
                        <img src="/themes/market/assets/images/profile-badge-influencer.png" alt="">
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <p class="font-paragraph-big-bold">Show Off Your Credentials</p>
                    <p><strong>Are you an NRA or USCCA certified instructor?</strong> What about a veteran or influencer? Our profile badges help show of your bona fides and establish trust with your audience.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="instructors-cta">
        <div class="container">
            <h2 class="text-center mb-4">The Premier Tools for Every Firearm Instructor</h2>
            <div class="row justify-content-center">
                <div class="col-12 col-md-auto">
                    <p class="instructors-cta__item"><i class="far fa-check mr-2 text-primary"></i>No Listing Fees - For Life!</p>
                </div>
                <div class="col-12 col-md-auto">
                    <p class="instructors-cta__item"><i class="far fa-check mr-2 text-primary"></i>Geolocated Marketing!</p>
                </div>
            </div>
            <div class="row justify-content-center mb-3">
                <div class="col-12 col-md-auto">
                    <p class="instructors-cta__item"><i class="far fa-check mr-2 text-primary"></i>No Monthly Hosting Fees!</p>
                </div>
                <div class="col-12 col-md-auto">
                    <p class="instructors-cta__item"><i class="far fa-check mr-2 text-primary"></i>Utilize our Powerful SEO!</p>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <a href="#form" class="btn btn-info d-none d-md-inline-block">Sign Me Up</a>
                    <a href="#m-form" class="btn btn-info d-inline-block d-md-none">Sign Me Up</a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('shop::home.uscca-link')
@endsection