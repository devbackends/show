@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.title') }}
@endsection

@section('content')

    <div class="account-layout">

        <div class="account-head mb-10">
            <span class="account-heading">
                {{ __('marketplace::app.shop.sellers.account.catalog.products.title') }}
            </span>

            <div class="account-action">
                <a href="{{ route('marketplace.account.products.search') }}" class="btn btn-primary btn-lg">
                    {{ __('marketplace::app.shop.sellers.account.catalog.products.create') }}
                </a>
            </div>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('marketplace.sellers.account.catalog.products.list.before') !!}

        <div class="account-items-list">

            {!! app('Webkul\Marketplace\DataGrids\Shop\ProductDataGrid')->render() !!}

        </div>

        {!! view_render_event('marketplace.sellers.account.catalog.products.list.after') !!}

    </div>

@endsection