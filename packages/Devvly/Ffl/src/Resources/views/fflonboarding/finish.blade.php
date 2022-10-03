@extends("ffl::fflonboarding.layouts.master")

@section('content')
<div class="container py-5 px-0">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <p class="ffl-form-finish__confirmation">{{__('ffl::app.finish.thank_you')}}</p>
            <p>{{__('ffl::app.finish.reviewing')}}</p>
        </div>
    </div>

    <div class="bg-dark ffl-form-finish__message mb-5">
        <div class="row">
            <div class="col-12 col-lg">
                <h2 class="h1 text-primary ffl-form-finish__title">{{__('ffl::app.finish.take_next_step')}}</h2>
                <h2 class="text-white  ffl-form-finish__title">{{__('ffl::app.finish.setup_your_shop')}}</h2>
            </div>
            <div class="col-12 col-lg-6">
                <p class="text-white"><span class="text-primary font-weight-bold">{{__('ffl::app.finish.2a_gun_show')}}</span>{{__('ffl::app.finish.take_next_step_message')}}</p>
                <a href="/customer/register" class="btn btn-primary btn-lg">{{__('ffl::app.finish.btn_sign_up_start_selling')}}</a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col col-lg-8 offset-lg-2">
            <div class="text-center">
                <img src="/images/gunshow-target-icon.svg" alt="">
            </div>
            <p class="paragraph-xl-bold text-center">{{__('ffl::app.finish.marketplace_vendor')}}</p>
            <p class="text-center"><span class="font-weight-bold">{{__('ffl::app.finish.something_to_sell')}}</span>{{__('ffl::app.finish.get_it_listed')}}</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 col-md-3 col-lg text-center">
            <p class="mb-0 font-weight-bold">{{__('ffl::app.finish.monthly_fee')}}</p>
            <p class="paragraph-lg-bold">$0</p>
        </div>
        <div class="col-12 col-md-3 col-lg text-center">
            <p class="mb-0 font-weight-bold">{{__('ffl::app.finish.processing_fee')}}</p>
            <p class="paragraph-lg-bold">2.9%</p>
        </div>
        <div class="col-12 col-md-3 col-lg text-center">
            <p class="mb-0 font-weight-bold">{{__('ffl::app.finish.transaction_fee')}}</p>
            <p class="paragraph-lg-bold">$0.30</p>
        </div>
        <div class="col-12 col-md-3 col-lg text-center">
            <p class="mb-0 font-weight-bold">{{__('ffl::app.finish.listing_fee')}}</p>
            <p class="mb-0 paragraph-lg-bold text-gray ">$0.99</p>
            <h5 class="mb-1"><span class="badge badge-primary">{{__('ffl::app.finish.free')}}</span></h5>
            <p class="font-weight-bold text-gray-dark">{{__('ffl::app.finish.limited_offer')}}</p>
        </div>
        <div class="col-12 col-md-3 col-lg text-center">
            <p class="mb-0 font-weight-bold">{{__('ffl::app.finish.commision_fee')}}</p>
            <p class="paragraph-lg-bold">2%</p>
        </div>
    </div>
    <div class="text-center">
        <a href="/customer/register" class="btn btn-primary btn-lg">{{__('ffl::app.finish.btn_sign_up_now')}}</a>
    </div>
</div>
@endsection