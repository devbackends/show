@extends('marketplace::shop.layouts.master')

@section('page_title')
    Community
@stop

@section('seo')
    <meta name="description" content="Community"/>
    <meta name="keywords" content="Community"/>

    <meta property="og:title" content="Community Page" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.2agunshow.com/marketplace/community" />
    <meta property="og:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="og:description" content="2A Gun Show Community" />

    <meta property="twitter:title" content="Community Page" />
    <meta property="twitter:type" content="website" />
    <meta property="twitter:url" content="https://www.2agunshow.com/marketplace/community" />
    <meta property="twitter:image" content="https://www.2agunshow.com/images/logo.png" />
    <meta property="twitter:description" content="2A Gun Show Community" />

@stop

@section('content-wrapper')

    @include('shop::home.community-links')
    @include('shop::home.uscca-link')

@endsection
