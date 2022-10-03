@extends('shop::customers.account.index')

@section('page_title')
    {{ __('rma::app.shop.customer.title') }}
@endsection

@if (auth()->guard('customer')->user())
    @section('page-detail-wrapper')
@else
    @section('content-wrapper')
        <div class="account-content row no-margin velocity-divide-page">
            <div class="account-layout full-width mt10">
@endif
<div class="customer-profile__content-wrapper">
    <div class="customer-profile__content-header d-flex">
    <h3 class="customer-profile__content-header-title">{{ __('rma::app.shop.customer-rma-index.heading') }}</h3>
    <div class="customer-profile__content-header-actions ml-auto">

            <a
            @if(!auth()->guard('customer')->user())
                href="{{ route('rma.customers.guestcreaterma') }}"
            @else
                href="{{ route('rma.customers.create') }}"
            @endif
            class="btn btn-outline-dark">
            {{ __('rma::app.shop.customer-rma-index.create') }}
        </a>
        </div>
    </div>

    {!! view_render_event('customer.account.rma.list.before') !!}
        <div class="account-table-content">

            {!! app('Webkul\RMA\DataGrids\RMAList')->render() !!}

        </div>
    {!! view_render_event('customer.account.rma.list.after') !!}
</div>
@if (! auth()->guard('customer')->user())
    </div>
</div>
@endif

@endsection
