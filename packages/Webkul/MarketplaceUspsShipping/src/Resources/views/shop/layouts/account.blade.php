@extends('marketplace::shop.layouts.master')

@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        @yield('content')

    </div>
@stop