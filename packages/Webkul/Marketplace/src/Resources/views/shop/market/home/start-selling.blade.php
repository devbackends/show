@extends('shop::layouts.master')

@section('content-wrapper')

<?php
$url = route('customer.register.index');
$customer = auth()->guard('customer')->user();
if ($customer) {
    $seller = \Webkul\Marketplace\Models\Seller::query()->where('customer_id', $customer->id)->first();
    if (!$seller) {
        $url = route('marketplace.account.seller.create');
    }
}
?>

<div class="become-member-section__header-bg py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3">
                <h1 class="promo-section__title">Become a seller!</h1>
                <p class="font-paragraph-big-bold text-white">Join the community</p>
            </div>
            <div class="col-12 col-lg-9">
                <p class="text-white font-paragraph-big mb-4"><span class="text-primary font-paragraph-big-bold">{{__('ffl::app.finish.2a_gun_show')}}</span>{{__('ffl::app.finish.take_next_step_message')}}</p>
                <a href="{{ $url }}" class="btn btn-primary btn-xl">{{__('ffl::app.finish.btn_sign_up_start_selling')}}</a>
            </div>
        </div>
    </div>
</div>

@include('shop::home.join-cta', [
    'url' => $url
])
@include('shop::home.start-selling-form')

@endsection