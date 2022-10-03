@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.reviews.title') }}
@endsection

@section('content')

    <div class="account-layout">

        <div class="account-head mb-10">
            <span class="account-heading">
                {{ __('marketplace::app.shop.sellers.account.reviews.title') }}
            </span>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('marketplace.sellers.account.reviews.list.before') !!}

        <div class="account-items-list">

            {!! app('Webkul\Marketplace\DataGrids\Shop\ReviewDataGrid')->render() !!}

        </div>

        {!! view_render_event('marketplace.sellers.account.reviews.list.after') !!}

    </div>

@endsection