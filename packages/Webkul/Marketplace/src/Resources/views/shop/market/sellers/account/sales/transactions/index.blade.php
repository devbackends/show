@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.transactions.title') }}
@endsection

@section('content')

    <div class="settings-page">
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('marketplace::app.shop.sellers.account.sales.transactions.title') }}</p>
            </div>
            <div class="settings-page__header-actions"></div>
        </div>

        <div class="settings-page__body">
            {!! view_render_event('marketplace.sellers.account.sales.transactions.list.before') !!}

            <div class="account-items-list">

                <div class="dashboard-stats">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="dashboard-box">
                                <div class="title">
                                    {{ __('marketplace::app.shop.sellers.account.sales.transactions.total-sale') }}
                                </div>

                                <div class="data">
                                    {{ core()->formatBasePrice($statistics['total_sale']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="dashboard-box">
                                <div class="title">
                                    {{ __('marketplace::app.shop.sellers.account.sales.transactions.total-payout') }}
                                </div>

                                <div class="data">
                                    {{ core()->formatBasePrice($statistics['total_payout']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">

                        <div class="dashboard-box">
                            <div class="title">
                                {{ __('marketplace::app.shop.sellers.account.sales.transactions.remaining-payout') }}
                            </div>

                            <div class="data">
                                {{ core()->formatBasePrice($statistics['remaining_payout']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="account-items-list">
                <div class="account-table-content">

                    {!! app('Webkul\Marketplace\DataGrids\Shop\TransactionDataGrid')->render() !!}

                </div>
            </div>

            </div>

            {!! view_render_event('marketplace.sellers.account.sales.transactions.list.after') !!}
        </div>

    </div>

@endsection