@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="row account-content velocity-divide-page">
        <div class="col-md-auto pr-0 customer-sidemenu">
            @include('shop::customers.account.partials.sidemenu')
        </div>

        <div class="col-md pl-0">
            @yield('page-detail-wrapper')
        </div>
    </div>
@endsection