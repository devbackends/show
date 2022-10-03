@extends('shop::layouts.master')

@section('content-wrapper')
<div class="account-content container-fluid">
    <div class="row">
        <div class="col-2dot4 px-0">
            @include('shop::customers.account.partials.sidemenu')
        </div>
        <div class="col-12 col-md-9dot6 px-0">
            @yield('page-detail-wrapper')
        </div>
    </div>
</div>

@endsection