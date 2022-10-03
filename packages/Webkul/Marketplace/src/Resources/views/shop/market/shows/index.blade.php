@extends('marketplace::shop.layouts.master')

@section('page_title')
    Guns Shows
@stop

@section('seo')
    <meta name="description" content="Guns Shows"/>
    <meta name="keywords" content="Guns Shows"/>
@stop

@section('content-wrapper')
    <div class="py-5">
        <div class="container">
            <div class="shows-list__head">
                <h1>All Gun shows</h1>
            </div>

            <show-wrapper-index  :shows='@json($shows->items())'></show-wrapper-index>


        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection