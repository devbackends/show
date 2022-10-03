@extends('marketplace::shop.layouts.master')

@section('page_title')
    Gun Ranges
@stop

@section('seo')
    <meta name="description" content="Gun Ranges"/>
    <meta name="keywords" content="Gun Ranges"/>
@stop

@section('content-wrapper')
    <div class="py-5">
        <div class="container">
            <div class="shows-list__head">
                <h1>All Gun Ranges</h1>
            </div>

            <gun-ranges-wrapper :gun-ranges='@json($gunRanges->items())'></gun-ranges-wrapper>

        </div>
    </div>
    @include('shop::home.recent-products')
    @include('shop::home.uscca-link')
@endsection
