@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.address.edit.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="row no-margin">
        <div class="col-sm-12">
            <div class="account-head mb-15">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i
                                class="icon icon-menu-back"></i></a></span>
                <span class="paragraph-big bold black">{{ __('shop::app.customer.account.address.edit.title') }}</span>
                <span></span>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.address.edit.before', ['address' => $address]) !!}

    <form method="post" action="{{ route('customer.address.edit', $address->id) }}" @submit.prevent="onSubmit">

        <div class="account-table-content">
            @method('PUT')
            @csrf

            {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.before', ['address' => $address]) !!}


            <?php $addresses = explode(PHP_EOL, (old('address1') ?? $address->address1)); ?>

            {{--              <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                            <label for="company_name">{{ __('shop::app.customer.account.address.edit.company_name') }}</label>
                            <input type="text"  class="control" name="company_name" value="{{ old('company_name') ?? $address->company_name }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.edit.company_name') }}&quot;">
                            <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                        </div>--}}
            <div class="row no-margin">
                <div class="col-sm-6">
                    <div class="control-group " :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name"
                               class="mandatory paragraph regular-font gray-dark">{{ __('shop::app.customer.account.address.create.first_name') }}</label>
                        <input type="text" class="control" name="first_name"
                               placeholder="{{ __('shop::app.customer.account.address.create.first_name') }}"
                               value="{{ old('first_name') ?? $address->first_name }}" v-validate="'required'"
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.first_name') }}&quot;">
                        <span class="control-error"
                              v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name"
                               class="mandatory   paragraph regular-font gray-dark">{{ __('shop::app.customer.account.address.create.last_name') }}</label>
                        <input type="text" class="control" name="last_name"
                               value="{{ old('last_name') ?? $address->last_name }}" v-validate="'required'"
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.last_name') }}&quot;">
                        <span class="control-error"
                              v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>
                </div>
            </div>

            <div class="row no-margin">
                <div class="col-sm-6">
                    <div class="control-group" :class="[errors.has('vat_id') ? 'has-error' : '']">
                        <label for="vat_id" class=" paragraph regular-font gray-dark">
                            {{ __('shop::app.customer.account.address.create.vat_id') }}
                        </label>
                        <input type="text" class="control" name="vat_id" value="{{ old('vat_id') ?? $address->vat_id }}"
                               v-validate=""
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.vat_id') }}&quot;"
                               placeholder="{{ __('shop::app.customer.account.address.create.vat_help_note') }}">
                        <span class="control-error" v-if="errors.has('vat_id')">@{{ errors.first('vat_id') }}</span>
                    </div>
                </div>
            </div>

            <div class="row no-margin">
                <div class="col-sm-12">
                    <div class="control-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                        <label for="address_0"
                               class="mandatory  paragraph regular-font gray-dark">{{ __('shop::app.customer.account.address.edit.street-address') }}</label>
                        <input type="text" class="control"
                               placeholder="{{ __('shop::app.customer.account.address.create.street-address') }}"
                               name="address1[]" value="{{ isset($addresses[0]) ? $addresses[0] : '' }}" id="address_0"
                               v-validate="'required'"
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.street-address') }}&quot;">
                        <span class="control-error"
                              v-if="errors.has('address1[]')">@{{ errors.first('address1[]') }}</span>
                    </div>
                </div>
            </div>

            @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
                @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                    <div class="row  no-margin">
                        <div class="col-sm-12">
                            <div class="control-group" style="margin-top: -25px;">
                                <input type="text" class="control" name="address1[{{ $i }}]" id="address_{{ $i }}"
                                       value="{{ $addresses[$i] ?? '' }}">
                            </div>
                        </div>
                    </div>
                @endfor
            @endif


            <div class="row  no-margin">
                <div class="col-sm-4">
                    <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                        <label for="city"
                               class="mandatory   paragraph regular-font gray-dark">{{ __('shop::app.customer.account.address.create.city') }}</label>
                        <input type="text" class="control" name="city" value="{{ old('city') ?? $address->city }}"
                               v-validate="'required|alpha_spaces'"
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;">
                        <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                    </div>
                </div>

                <div class="col-sm-4">
                    @include ('shop::customers.account.address.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])
                </div>
                <div class="col-sm-4">
                    <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                        <label for="postcode"
                               class="mandatory   paragraph regular-font gray-dark">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                        <input type="text" class="control" name="postcode"
                               value="{{ old('postcode') ?? $address->postcode }}"
                               placeholder="{{ __('shop::app.customer.account.address.create.postcode') }}"
                               v-validate="'required'"
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.postcode') }}&quot;">
                        <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                    </div>
                </div>
            </div>
            <div class="row  no-margin">
                <div class="col-sm-4">
                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone"
                               class="mandatory  paragraph regular-font gray-dark">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                        <input type="text" class="control" name="phone" value="{{ old('phone') ?? $address->phone }}"
                               placeholder="{{ __('shop::app.customer.account.address.create.phone') }}"
                               v-validate="'required'"
                               data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>
                </div>
            </div>
            {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.after', ['address' => $address]) !!}
            <div class="row  no-margin">
                <div class="col-sm-12">
                    <div class="button-group">
                        <button class="theme-btn" type="submit">
                            Edit Address
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {!! view_render_event('bagisto.shop.customers.account.address.edit.after', ['address' => $address]) !!}
@endsection