@extends('shop::layouts.master')

@section('page_title')
{{ __('marketplace::app.shop.sellers.account.profile.create-title') }}
@endsection

@section('content-wrapper')

    <seller-onboarding :customer="{{auth()->guard('customer')->user()}}" submit-url="{{route('marketplace.account.seller.onboarding.submit')}}" />

@endsection

@push('scripts')
    <script src="{{config('services.2acommerce.gateway_url') . '/tokenizer/tokenizer.js'}}"></script>
@endpush