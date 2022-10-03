@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('page-detail-wrapper')
<div class="settings-page">

    <div class="settings-page__header">
        <div class="settings-page__header-title">
            <p>{{ __('shop::app.customer.account.profile.index.title') }}</p>
        </div>
        <div class="settings-page__header-actions"></div>
    </div>

    <div class="settings-page__body">

        {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

        <div class="row profile-info">
            <div class="col-6 col-lg-3">
                <div class="form-group">
                    <p class="profile-info__label">{{ __('shop::app.customer.account.profile.fname') }}</p>
                    <p class="profile-info__data">{{ $customer->first_name }}</p>
                </div>
            </div>
            <div class="col-6 col-lg-9">
                <div class="form-group">
                    <p class="profile-info__label">{{ __('shop::app.customer.account.profile.lname') }}</p>
                    <p class="profile-info__data">{{ $customer->last_name }}</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="form-group">
                    <p class="profile-info__label">{{ __('shop::app.customer.account.profile.gender') }}</p>
                    <p class="profile-info__data">{{ $customer->gender ?? '-' }}</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="form-group">
                    <p class="profile-info__label">{{ __('shop::app.customer.account.profile.dob') }}</p>
                    <p class="profile-info__data">{{ $customer->date_of_birth ?? '-' }}</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="form-group">
                    <p class="profile-info__label">{{ __('shop::app.customer.account.profile.email') }}</p>
                    <p class="profile-info__data">{{ $customer->email }}</p>
                </div>
            </div>
        </div>

        <a href="{{ route('customer.profile.edit') }}" class="btn btn-outline-primary"><i class="far fa-pencil"></i><span>{{ __('shop::app.customer.account.profile.index.edit') }}</span></a>

        <div class="mt-5">
            {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
        </div>
    </div>

    <div class="account-table-content profile-page-content" style="display:none;">

        <button type="submit" class="theme-btn mb20" @click="showModal('deleteProfile')" style="display:none;">
            {{ __('shop::app.customer.account.address.index.delete') }}
        </button>

        <form method="POST" action="{{ route('customer.profile.destroy') }}" @submit.prevent="onSubmit">
            @csrf

            <modal id="deleteProfile" :is-open="modalIds.deleteProfile">
                <h3 slot="header">{{ __('shop::app.customer.account.address.index.enter-password') }}
                </h3>
                <i class="rango-close"></i>

                <div slot="body">
                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password" class="required">{{ __('admin::app.users.users.password') }}</label>
                        <input type="password" v-validate="'required|min:6|max:18'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;" />
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="page-action">
                        <button type="submit" class="theme-btn mb20" style="display:none;">
                            {{ __('shop::app.customer.account.address.index.delete') }}
                        </button>
                    </div>
                </div>
            </modal>
        </form>
    </div>

</div>

@endsection