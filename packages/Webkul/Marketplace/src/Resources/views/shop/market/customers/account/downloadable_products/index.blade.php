@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.downloadable_products.title') }}
@endsection

@section('page-detail-wrapper')

    <div class="settings-page">
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('shop::app.customer.account.downloadable_products.title') }}</p>
            </div>
            <div class="settings-page__header-actions"></div>
        </div>
        <div class="settings-page__body">
        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

        {!! app('Webkul\Shop\DataGrids\DownloadableProductDataGrid')->render() !!}

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}
        </div>
    </div>








@endsection