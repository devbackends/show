@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@push('css')
<style type="text/css">
    .account-head {
        height: 50px;
    }

    .remove-icon {
        right: 15px;
        font-size: 22px;
        height: 24px;
        text-align: center;
        position: absolute;
        border-radius: 50%;
        color: #333;
        width: 24px;
        padding: 0px;
        top: 10px;
    }

    .remove-icon:before {
        content: "x";
    }
</style>
@endpush


@section('page-detail-wrapper')
<div class="customer-profile__content-wrapper">
    <div class="customer-profile__content-header">
        <a href="{{ route('customer.account.index') }}" class="customer-profile__content-header-back">
            <i class="far fa-chevron-left"></i>
        </a>
        <h3 class="customer-profile__content-header-title">
            {{ __('shop::app.customer.account.profile.index.title') }}
        </h3>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

    <div class="row">
        <div class="col-md-3">
            <div class="form-group customer-profile__content-profile-info">
                <label>{{ __('shop::app.customer.account.profile.fname') }}</label>
                <p>{{ $customer->first_name }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group customer-profile__content-profile-info">
                <label>{{ __('shop::app.customer.account.profile.lname') }}</label>
                <p>{{ $customer->last_name }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group customer-profile__content-profile-info">
                <label>{{ __('shop::app.customer.account.profile.gender') }}</label>
                <p>{{ $customer->gender ?? '-' }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group customer-profile__content-profile-info">
                <label>{{ __('shop::app.customer.account.profile.dob') }}</label>
                <p>{{ $customer->date_of_birth ?? '-' }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group customer-profile__content-profile-info">
                <label>{{ __('shop::app.customer.account.profile.email') }}</label>
                <p>{{ $customer->email }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('customer.profile.edit') }}" class="btn btn-outline-dark">
            <i class="far fa-pencil"></i>{{ __('shop::app.customer.account.profile.index.edit') }}
            </a>
        </div>
    </div>

    <div class="account-table-content profile-page-content">

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
{!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
@endsection