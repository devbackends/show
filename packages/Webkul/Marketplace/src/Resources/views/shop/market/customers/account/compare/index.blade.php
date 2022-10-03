@extends('shop::customers.account.index')

@include('shop::guest.compare.compare-products')

@section('page_title')
    {{ __('velocity::app.customer.compare.compare_similar_items') }}
@endsection

@push('css')
@endpush

@section('page-detail-wrapper')
    <compare-product></compare-product>
@endsection
