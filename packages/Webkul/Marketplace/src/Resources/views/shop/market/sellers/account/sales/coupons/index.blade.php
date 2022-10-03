@extends('marketplace::shop.layouts.account')

@section('page_title')
    Coupons
@endsection


@section('content')

    <div class="settings-page">

        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>Coupons</p>
            </div>
            <div class="settings-page__header-actions">
                    <a href="{{ route('marketplace.account.coupons.create') }}" class="btn btn-primary">
                        Add Coupon
                    </a>
            </div>
        </div>

        <div class="settings-page__body">
            {!! app('Webkul\Marketplace\DataGrids\Shop\CartRuleDataGrid')->render() !!}
        </div>

    </div>

@endsection

