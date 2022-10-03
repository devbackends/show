@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.reviews.title') }}
@endsection

@section('content')

    <div class="settings-page">

        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('marketplace::app.shop.sellers.account.reviews.title') }}</p>
            </div>
            <div class="settings-page__header-actions"></div>
        </div>



        {!! view_render_event('marketplace.sellers.account.reviews.list.before') !!}

        <div class="settings-page__body">
                {!! app('Webkul\Marketplace\DataGrids\Shop\ReviewDataGrid')->render() !!}
        </div>

        {!! view_render_event('marketplace.sellers.account.reviews.list.after') !!}

    </div>

@endsection