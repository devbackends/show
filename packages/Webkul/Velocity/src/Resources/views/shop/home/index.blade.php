@extends('shop::layouts.master')

@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')

@php
    $channel = core()->getCurrentChannel();

    $homeSEO = $channel->home_seo;

    if (isset($homeSEO)) {
        $homeSEO = json_decode($channel->home_seo);

        $metaTitle = $homeSEO->meta_title;

        $metaDescription = $homeSEO->meta_description;

        $metaKeywords = $homeSEO->meta_keywords;
    }
@endphp

@section('page_title')
    {{ isset($metaTitle) ? $metaTitle : "" }}
@endsection

@section('head')

    @if (isset($homeSEO))
        @isset($metaTitle)
            <meta name="title" content="{{ $metaTitle }}" />
        @endisset

        @isset($metaDescription)
            <meta name="description" content="{{ $metaDescription }}" />
        @endisset

        @isset($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}" />
        @endisset
    @endif
@endsection

@push('css')
    <style type="text/css">
        .product-price span:first-child, .product-price span:last-child {
            font-size: 18px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content-wrapper')
    @if($metaData->path_hero_image && $metaData->path_hero_image !== 'default')
        <div class="row">
            <div class="col-md-12">
                @if($metaData->hero_image_link)
                    <a href="{{$metaData->hero_image_link}}" target="_blank" class="hero-image">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($metaData->path_hero_image) }}" alt="">
                    </a>
                @else
                    <div class="hero-image">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($metaData->path_hero_image) }}" alt="">
                    </div>
                @endif
            </div>
        </div>
    @elseif($metaData->path_hero_image && $metaData->path_hero_image === 'default')
        <div class="row">
            <div class="col-md-12">
                <div class="hero-image">
                    <img src="{{asset('vendor/webkul/saas/assets/images/hero_image.png')}}" alt="">
                </div>
            </div>
        </div>
    @endif
@endsection

@section('full-width-content-wrapper')
    {!! view_render_event('bagisto.shop.home.content.before') !!}
    @if ($metaData)
        {!! DbView::make($metaData)->field('home_page_content')->render() !!}
    @else
        @include('shop::home.featured-products')
    @endif
    {{ view_render_event('bagisto.shop.home.content.after') }}
@endsection
@push('scripts')
    <script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>
    <script src="{{ asset('vendor/devvly/customblocks/assets/js/app.js') }}"></script>
@endpush

