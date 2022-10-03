@extends('shop::layouts.master')

@section('page_title')
    Internal Server Error
@stop

@section('content-wrapper')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9">
                <div class="text-center py-5">
                    <img src="/images/404.svg" alt="404 page" class="img-fluid">
                    <h1>500</h1>
                    <p class="head">Internal Server Error</p>
                    <a href="/" class="btn btn-outline-primary">Try going to the home page</a>
                </div>
            </div>
        </div>
    </div>
@endsection
