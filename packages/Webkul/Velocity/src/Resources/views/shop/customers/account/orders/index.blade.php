@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')

<div class="customer-profile__content-wrapper">
    <div class="customer-profile__content-header">
        <a href="{{ route('customer.account.index') }}" class="customer-profile__content-header-back">
            <i class="far fa-chevron-left"></i>
        </a>
        <h3 class="customer-profile__content-header-title">
            {{ __('shop::app.customer.account.order.index.title') }}
        </h3>
    </div>


    {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

    <div class="customer-profile__content-table account-items-list">
        <div class="account-table-content">

            {!! app('Webkul\Shop\DataGrids\OrderDataGrid')->render() !!}

        </div>
    </div>

</div>



{!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@endsection