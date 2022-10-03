@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.profile.create-title') }}
@endsection

@section('content')

    <div class="account-layout">

        <form method="post" action="{{ route('marketplace.account.seller.store') }}" @submit.prevent="onSubmit">
            <div class="account-head mb-10">

                <span class="account-heading">{{ __('marketplace::app.shop.sellers.account.profile.create-title') }}</span>

                <div class="account-action">

                    @if (! $seller)
                        <button type="submit" class="btn btn-primary btn-lg">
                            {{ __('marketplace::app.shop.sellers.account.profile.save-btn-title') }}
                        </button>
                    @endif

                </div>

                <div class="horizontal-rule"></div>

            </div>

            {!! view_render_event('marketplace.sellers.account.profile.create.before') !!}

            <div class="account-table-content">

                @if (! $seller)
                
                    @csrf()

                    <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                        <label for="url" class="required">{{ __('marketplace::app.shop.sellers.account.profile.url') }}</label>
                        <input type="text" class="control" name="url" v-validate="'required'" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.profile.url') }}&quot;">
                        <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                    </div>
                @else

                    {{ __('marketplace::app.shop.sellers.account.profile.waiting-for-approval') }}

                @endif

            </div>


            {!! view_render_event('marketplace.sellers.account.profile.create.after') !!}

        </form>

    </div>
    
@endsection