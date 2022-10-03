@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.address.edit.page-title') }}
@endsection

@section('page-detail-wrapper')
<div class="settings-page">
    <form method="post" action="{{ route('customer.address.edit', $address->id) }}" @submit.prevent="onSubmit">
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('shop::app.customer.account.address.edit.title') }}</p>
            </div>
            <div class="settings-page__header-actions">
            <button type="submit" class="btn btn-primary">Add address</button>
            </div>
        </div>
        {!! view_render_event('bagisto.shop.customers.account.address.edit.before', ['address' => $address]) !!}
        <div class="settings-page__body">
            @method('PUT')
            @csrf

            {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.before', ['address' => $address]) !!}

            <?php $addresses = explode(PHP_EOL, (old('address1') ?? $address->address1)); ?>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name">{{ __('shop::app.customer.account.address.create.first_name') }}</label>
                        <input type="text" class="form-control" name="first_name" placeholder="{{ __('shop::app.customer.account.address.create.first_name') }}" value="{{ old('first_name') ?? $address->first_name }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.first_name') }}&quot;">
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name">{{ __('shop::app.customer.account.address.create.last_name') }}</label>
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') ?? $address->last_name }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.last_name') }}&quot;">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group" :class="[errors.has('vat_id') ? 'has-error' : '']">
                        <label for="vat_id">{{ __('shop::app.customer.account.address.create.vat_id') }}</label>
                        <input type="text" class="form-control" name="vat_id" value="{{ old('vat_id') ?? $address->vat_id }}" v-validate="" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.vat_id') }}&quot;" placeholder="{{ __('shop::app.customer.account.address.create.vat_help_note') }}">
                        <span class="control-error" v-if="errors.has('vat_id')">@{{ errors.first('vat_id') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                        <label for="address_0">{{ __('shop::app.customer.account.address.edit.street-address') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('shop::app.customer.account.address.create.street-address') }}" name="address1[]" value="{{ isset($addresses[0]) ? $addresses[0] : '' }}" id="address_0" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.street-address') }}&quot;">
                        <span class="control-error" v-if="errors.has('address1[]')">@{{ errors.first('address1[]') }}</span>
                    </div>
                </div>

                @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
                @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                    <div class="col-12">
                        <div class="form-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                            <input type="text" class="control" name="address1[{{ $i }}]" id="address_{{ $i }}" value="{{ $addresses[$i] ?? '' }}">
                        </div>
                    </div>
                    @endfor
                    @endif
            </div>
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="[errors.has('city') ? 'has-error' : '']">
                        <label for="city">{{ __('shop::app.customer.account.address.create.city') }}</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city') ?? $address->city }}" v-validate="'required|alpha_spaces'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;">
                        <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                        @include ('shop::customers.account.address.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                        <label for="postcode">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                        <input type="text" class="form-control" name="postcode" value="{{ old('postcode') ?? $address->postcode }}" placeholder="{{ __('shop::app.customer.account.address.create.postcode') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.postcode') }}&quot;">
                        <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') ?? $address->phone }}" placeholder="{{ __('shop::app.customer.account.address.create.phone') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>
                </div>
                {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.after', ['address' => $address]) !!}
            </div>
        </div>
        {!! view_render_event('bagisto.shop.customers.account.address.edit.after', ['address' => $address]) !!}
    </form>
</div>


@endsection