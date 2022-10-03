@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace_usps::app.shop.sellers.usps.manage-configuration') }}
@endsection

@section('content')
    <?php
        if (app('Webkul\Marketplace\Repositories\SellerRepository')->isSeller(auth()->guard('customer')->user()->id)) {
            $customerId = auth()->guard('customer')->user()->id;

            $sellerId = app('Webkul\Marketplace\Repositories\SellerRepository')->findOneWhere(['customer_id'=> $customerId]);

            $sellerId = $sellerId->id;
        }

    ?>

    <div class="account-layout">

        <form method="post" action="{{ route('marketplaceusps.credentials.store', $sellerId) }}" enctype="multipart/form-data">
            <div class="account-head seller-profile-edit mb-10">

                <span class="account-heading">{{ __('marketplace_usps::app.shop.sellers.usps.manage-configuration') }}</span>

                <div class="account-action">

                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('marketplace::app.shop.sellers.account.profile.save-btn-title') }}
                    </button>

                </div>

                <div class="horizontal-rule"></div>

            </div>

            <div class="account-table-content">

                @csrf()

                <accordian :title="'{{ __('marketplace_usps::app.shop.sellers.usps.usps-config') }}'" :active="true">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('account_id') ? 'has-error' : '']">
                            <label for="text" class="required">{{ __('marketplace_usps::app.shop.sellers.usps.account-id') }}<i class="export-icon"></i> </label>
                            <input type="text" v-validate="'required'" class="control" name="account_id" value="{{ $credentials->account_id ?? ''}}" data-vv-as="&quot;{{ __('marketplace_usps::app.shop.sellers.usps.account-id') }}&quot;">
                            <span class="control-error" v-if="errors.has('account_id')">@{{ errors.first('account_id') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                            <label for="text" class="required">{{ __('marketplace_usps::app.shop.sellers.usps.password') }}<i class="export-icon"></i> </label>
                            <input type="password" v-validate="'required'" class="control" name="password" value="{{ $credentials->password ?? ''}}" data-vv-as="&quot;{{ __('marketplace_usps::app.shop.sellers.usps.password') }}&quot;">
                            <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                        </div>
                    </div>
                </accordian>
            </div>
        </form>
    </div>
@endsection