@extends('marketplace::shop.layouts.master')

@section('page_title')
    FFLs
@stop

@section('seo')
    <meta name="description" content="FFLs"/>
    <meta name="keywords" content="FFLs"/>
@stop

@section('content-wrapper')
<div class="ffl-list__hero">
    <h2 class="h1">Find FFLs</h2>
</div>
    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-9 mb-5 mb-sm-0">
                    <div class="shows-list__head">
                        <p class="font-paragraph-big-bold">All FFLs</p>
                    </div>
                    <ffl-wrapper :ffls='@json($ffls->items())'></ffl-wrapper>
                </div>
                <div class="col-12 col-sm-3">
                    <a href="/ffl-signup"><img src="/themes/market/assets/images/ffl-list-banner.jpg" alt="" class="w-100 h-auto"></a>
                </div>
            </div>
        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection