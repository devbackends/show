@extends('marketplace::shop.layouts.master')

@section('content-wrapper')
<div class="account-content container-fluid">
    <div class="row">
        <div class="col-2dot4 px-0 {{ str_contains(url()->current(), '/account/catalog/products/create') || str_contains(url()->current(), '/marketplace/account/catalog/products/edit') ? 'd-none' : ''  }}">
            @include('shop::customers.account.partials.sidemenu')
        </div>
        <div class="col-12 {{ str_contains(url()->current(), '/account/catalog/products/create') || str_contains(url()->current(), '/marketplace/account/catalog/products/edit') ? 'px-0' : 'col-lg-9dot6 px-0'  }}">
            @yield('content')
        </div>
    </div>
</div>

@stop