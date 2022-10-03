@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.reviews.product-review-page-title') }}
@endsection

@php
    $ratings = [
        '', '', '', ''
    ];

    $ratings = [
        10, 30, 20, 15, 25
    ];

    $totalReviews = 25;
    $totalRatings = array_sum($ratings);

@endphp

@push('css')
    <style>
        .reviews {
            display: none !important;
        }
    </style>
@endpush

@section('content-wrapper')
    <div class="container review-page-container py-5">
        <div class="row">
            <div class="col-12 col-md-4">
                @include ('shop::products.view.small-view', ['product' => $product])
            </div>
            <div class="col-12 col-md-8">
                <a href="{{ route('shop.product.index', $product->url_key) }}" class="mb-4">
                    <h2>{{ $product->name }}</h2>
                </a>
                <h3>Rating and Reviews</h3>
                @include ('shop::products.view.reviews')
            </div>
        </div>
    </div>
@endsection