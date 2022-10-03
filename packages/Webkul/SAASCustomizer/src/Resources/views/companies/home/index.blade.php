@extends('saas::companies.layouts.master')

@section('head')
            <meta name="title" content="2AGUNSHOW"/>
            <meta name="description" content="Gun Shows"/>
            <meta name="keywords" content="Gun Shows"/>
    @push('scripts')
        <link rel="stylesheet" href="{{ asset('css/gun.css') }}">
    @endpush
@endsection

@section('content-wrapper')

    <div class="top-container">
        <div class="logo-container">
            <span class="icon gunshow-logo-icon"></span>
        </div>

        <img src="{{asset('images/shooter.png')}}" style="width: 100%;">
        <div class="top-title">
            <h1 class="custom_h1"><span class="yellow">2A GUN SHOW HAS</span> THE LOWEST ONLINE TRANSACTION FEES IN THE
                INDUSTRY</h1>
            <div class="sell-button-container"><a href="https://www.2agunshow.com/marketplace/start-selling"> {{--{{route('company.create.store')}}--}}
                    <button class="btn btn-primary custom_button">Sign up and start selling NOW!</button>
                </a></div>
        </div>
        <div class="triangle-container">
            <span class="icon triangle-logo-icon"></span>
        </div>
    </div>
    <div class="fees-container">
        <div class="flat-fees">
            <h1 class="custom_h1 black align-right no-margin">FLAT <span class="yellow black-bg">2.8%</span></h1>
            <h1 class="custom_h1 black align-right no-margin">TRANSACTION FEE</h1>
            <h1 class="custom_h1 black align-right no-margin">AND <span class="yellow black-bg">$0.30</span> PER</h1>
            <h1 class="custom_h1 black align-right no-margin">TRANSACTION</h1>
        </div>
        <div class="listing-fees">
            <p class="black_span">No listing fees</p>
            <p class="black_span">No marketing fees</p>
            <p class="black_span">No surcharges</p>
            <p class="light_span">No hidden fees EVER</p>

        </div>

    </div>
    <div class="sell-button-container">
        <a href="https://www.2agunshow.com/marketplace/start-selling"> {{--{{route('company.create.store')}}--}}
            <button class="btn btn-primary custom_button">Sign up and start selling NOW!</button>
        </a>
    </div>

    <div class="black-container" style="margin-top: 5vw;">
        <div>
            <h3 class="custom_h3">What makes 2A Gun Show different from other online gun sellers?</h3>
            <h2 class="custom_h2 yellow">Absolutely Everything!</h2>
        </div>

    </div>
    <div class="about-container">
        <div>
            <p>
                We are building a commerce application and integrated marketplace that puts the power of the web
                directly in your hands. <strong>2 A Gun Show has the lowest online transaction fees in the industry, at
                    a flat 2.8% + $0.30 per transaction. No listing fees. No marketing fees. No surcharges. No hidden
                    fees EVER.</strong> <a class="bold" href="{{route('saas.home.pricing-detail')}}">Click here to see pricing details.</a> <br><br>

                Most online gun marketplaces are an intermediary service, charging their vendors listing fees and
                excessive transaction fees in addition to your standard credit card processing fees, and sometimes
                charging fees after the sale is complete. This greatly increases the cost for the proprietor to be able
                to run their store by eating into their profit and decreasing their margin. We are the payment
                processing company! Say goodbye to the intermediary services and burdensome fees.<br><br>

                We provide you with your own webstore with your own domain running on our powerful engine and payment
                processing services. You can use it as your primary website, or as the webstore function only. Here’s
                where the real magic happens–whatever product you list in your store will automatically sync up with the
                app.2agun.show marketplace, where we highlight and showcase your products to our social shopping
                platform. Our system automatically gives you priority placement in the 2A Gun Show marketplace based on
                geolocation data and we drive foot traffic to your store for potential upsales.<br><br>

                Guess what else? Our system can also be used as your point of sale engine in your physical store,
                automatically syncronizing with your web products.<br><br>

                Consider this: A single service to manage your in-store and online sales, with a simple and low
                transaction rate, plus free marketing through our main website.<br>
            </p>

        </div>
    </div>

@endsection

