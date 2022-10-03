@extends('marketplace::shop.layouts.master')

@section('page_title')
Instructors
@stop

@section('seo')
<meta name="description" content="Instructors" />
<meta name="keywords" content="Instructors" />
@stop

@section('content-wrapper')
<div class="instructors">
    <div class="instructors__home-hero">
        <h2 class="h1">Instructors</h2>
    </div>
    <div class="py-5">
        <div class="container text-center pb-5">
            <h2 class="text-info-dark">promote your classes with 2a gun show!</h2>
            <div class="row mb-5">
                <div class="col-12 col-md-8 offset-md-2 mb-5">
                    <p>Help students find your firearms courses by listing with 2A Gun Show. 2AGS's powerful platform helps students find your business by geotagging your location. That way, anyone searching for courses in your area can find YOU!</p>
                    <p class="font-weight-bold mb-4">Instructors who sign up with 2A will receive:</p>
                    <div class="row instructors__home-intro-icons mb-4">
                        <div class="col-12 col-sm-4">
                            <div>
                                <i class="far fa-store"></i>
                                <p>Free Webstore to Promote Your Courses</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div>
                                <i class="far fa-calendar-check"></i>
                                <p>Student Registration Functionality </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div>
                                <i class="far fa-calendar-edit"></i>
                                <p>Class Event Management</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <a href="/customer/register" class="btn btn-primary btn-lg">Sign Up Now!</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="instructors__home-highlighted mb-5">
                <div class="row">
                    <div class="col-12 col-md-6 px-0 px-md-3">
                        <div class="position-relative instructors__home-highlighted-image">
                            <img src="/themes/market/assets/images/find-instructor-image.jpg" alt="" class="w-100 h-100">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <ul>
                            <li>
                                <p><i class="far fa-check"></i>No Listing Fees - For Life!</p>
                            </li>
                            <li>
                                <p><i class="far fa-check"></i>No Monthly Hosting Fees!</p>
                            </li>
                            <li>
                                <p><i class="far fa-check"></i>Geolocated Marketing!</p>
                            </li>
                            <li>
                                <p><i class="far fa-check"></i>Utilize our Powerful SEO!</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="shows-list__head">
                <p class="font-paragraph-xl-bold">All Instructors</p>
            </div>
            <instructor-wrapper :instructors='@json($instructors->items())'></instructor-wrapper>
        </div>
    </div>
</div>
@include('shop::home.recent-products')
@include('shop::home.uscca-link')
@endsection