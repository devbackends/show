@extends('marketplace::shop.layouts.master')

@section('page_title')
    Clubs and Associations
@stop

@section('seo')
    <meta name="description" content="Clubs"/>
    <meta name="keywords" content="Clubs"/>
@stop

@section('content-wrapper')
    <div class="py-5">
        <div class="container">
            <div class="shows-list__head">
                <h1>All Clubs and Associations</h1>
            </div>

            <club-wrapper :clubs='@json($clubs->items())'></club-wrapper>

        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')

@endsection