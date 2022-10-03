@extends('shop::customers.account.index')

@section('page_title')
    {{ __('rma::app.shop.customer.title') }}
@endsection

@if (auth()->guard('customer')->user())
    @section('page-detail-wrapper')
@else
    @section('content-wrapper')

@endif

<div class="settings-page">

    <div class="settings-page__header">
        <div class="settings-page__header-title">
            <p>{{ __('rma::app.shop.customer-rma-index.heading') }}</p>
        </div>
        <div class="settings-page__header-actions">
            <a
            @if(!auth()->guard('customer')->user())
                href="{{ route('rma.customers.guestcreaterma') }}"
            @else
                href="{{ route('rma.customers.create') }}"
            @endif
            class="btn btn-outline-gray">
            {{ __('rma::app.shop.customer-rma-index.create') }}
            </a>
        </div>
    </div>

    {!! view_render_event('customer.account.rma.list.before') !!}
    <div class="settings-page__body">
        {!! app('Webkul\RMA\DataGrids\RMAList')->render() !!}
    </div>
    {!! view_render_event('customer.account.rma.list.after') !!}

</div>







@if (! auth()->guard('customer')->user())

@endif

@endsection
