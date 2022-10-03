@extends('shop::layouts.master')

@section('page_title')
    {{ __('admin::app.error.404.page-title') }}
@stop

@section('content-wrapper')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9">
                <div class="text-center py-5">
                    <img src="/images/404.svg" alt="404 page" class="img-fluid">
                    <h1>404</h1>
                    <p class="head">We can't find the page you are looking for.</p>
                    <a href="/" class="btn btn-outline-primary">Try going to the home page</a>
                </div>
            </div>
        </div>
    </div>

    <div class="error-404-community-section">
        @include('shop::home.community-links')
    </div>
@endsection
