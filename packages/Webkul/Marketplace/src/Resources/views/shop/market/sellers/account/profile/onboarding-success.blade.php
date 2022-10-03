@extends('shop::layouts.master')

@section('page_title')
    Account Upgrade
@endsection

@section('content-wrapper')
    <div class="container py-5 seller-signup">
        <div id="success">
            <div class="row">
                <div class="col">
                    <div class="form-section seller-signup__success">
                        <h3 class="form-section__title">You are ready to go!</h3>
                        <p>Getting underwriting for your credit card processing can sometimes take a few days, so be patient while we get this set up for you. In the meantime, let’s get your store set up by <a href="{{route('marketplace.account.products.create')}}" class="font-weight-bold">creating your first product</a>. You will be able to begin accepting cash payments immediately.</p>
                        <div class="seller-signup__success-cta">
                            <div class="row align-items-center">
                                <div class="col-12 col-md">
                                    <p class="seller-signup__success-cta-text">You are ready to start selling!</p>
                                </div>
                                <div class="col-12 col-md-auto">
                                    <a href="{{route('marketplace.account.products.create')}}" class="btn btn-primary">Create Product</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection