@extends("ffl::fflonboarding.layouts.master")
@section('header')
<div class="row header d-none d-lg-block">
    <div class="col-12">
        <div class="container header__content">
            <div class="navbar-brand">
                <a href="/">
                    <img src="{{asset('images/ffllogo.svg')}}" />
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
@section('content')

<div class="row landing__wrapper">
    <div class="landing__wrapper-bg"></div>
    <div class="col-12">
        <div class="container px-0">
            <div class="row d-block d-lg-none">
                <div class="col-12">
                    <a href="/">
                        <img class="landing__logo-mobile" src="{{asset('images/ffllogo.svg')}}" />
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <p class="ffl-network-logo">{{__('ffl::app.landing.ffl_network')}}</p>
                    <h1 class="landing__title">{{__('ffl::app.landing.bring_customers')}}</h1>
                    <div class="landing__button">
                        <a href="{{route('ffl.onboarding.form')}}" class="btn btn-lg btn-primary w-100">
                            {{__('ffl::app.buttons.join')}}
                        </a>
                    </div>
                    <h3 class="landing__promo">{{__('ffl::app.landing.100%_free')}}</h3>
                </div>
                <div class="col-xl-6 col-lg-8">
                    <div class="landing__video">
                        <iframe src="https://www.youtube.com/embed/880gIla_iow?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="landing__text">
                        <p>{{__('ffl::app.landing.ffl_holder_3')}}</p>
                        <h3 class="landing__text-highlighted">{{__('ffl::app.landing.ffl_holder_2')}}</h3>
                        <p>{{__('ffl::app.landing.ffl_holder_4')}}</p>
                        <p>{{__('ffl::app.landing.ffl_holder_1')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection