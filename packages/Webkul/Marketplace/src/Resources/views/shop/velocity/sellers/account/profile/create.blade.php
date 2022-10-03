@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.profile.create-title') }}
@endsection

@section('content')

    <div class="account-layout right m10">

        <form method="post" action="{{ route('marketplace.account.seller.store') }}" @submit.prevent="onSubmit">
            <div class="account-head mb-10">

                <span class="paragraph-big bold">{{ __('marketplace::app.shop.sellers.account.profile.create-title') }}</span>

                <div class="account-action">

                    @if (! $seller)
                        <a onclick="document.getElementById('myCheck').click();" class="light-black-bordered-button" style="cursor: pointer;">
                            <span><i class="far fa-save padding-sides-5"></i></span>
                            <span>Save</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg hide" id="myCheck">
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

                    <div class="control-group col-6" :class="[errors.has('url') ? 'has-error' : '']">
                        <label for="url" class="required paragraph regular-font gray-dark">{{ __('marketplace::app.shop.sellers.account.profile.url') }}</label>
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