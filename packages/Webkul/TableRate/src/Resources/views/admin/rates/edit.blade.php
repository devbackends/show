@extends('admin::layouts.content')

@section('page_title')
    {{ __('tablerate::app.admin.shipping-rates.edit-title') }}
@stop

@section('content')

    <div class="content">
    <form method="POST" action="{{ route ('admin.tablerate.shipping_rates.update', $shippingRate->id)}}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            @csrf
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/tablerate/supersets') }}';"></i>

                        {{ __('tablerate::app.admin.shipping-rates.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('tablerate::app.admin.shipping-rates.save-rate-btn') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <accordian :title="'{{ __('tablerate::app.admin.shipping-rates.edit-shipping-rate') }}'" :active="true" style="width:130%;">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('tablerate_superset_id') ? 'has-error' : '']">
                                <label for="tablerate_superset_id" class="required">{{ __('tablerate::app.admin.superset-rates.superset-name') }}</label>
                                <select class="control" v-validate="'required'" id="tablerate_superset_id" name="tablerate_superset_id" data-vv-as="&quot;{{ __('tablerate::app.admin.superset-rates.superset-name') }}&quot;">
                                    <option value="">{{ __('tablerate::app.admin.superset-rates.select-superset') }}</option>
                                    @foreach($superSets as $method)
                                        <option value="{{ $method->id }}" @if($shippingRate->tablerate_superset_id == $method->id) selected @endif>{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('tablerate_superset_id')">@{{ errors.first('tablerate_superset_id') }}</span>
                            </div>
                            
                            @include ('shop::customers.account.address.country-state', ['countryCode' => $shippingRate->country, 'stateCode' => $shippingRate->state])

                            <div class="control-group" :class="[errors.has('weight_from') ? 'has-error' : '']">
                                <label for="weight_from" class="required">{{ __('tablerate::app.admin.shipping-rates.weight-from') }}</label>
                                <input type="text" v-validate="'required|decimal:4|min_value:0.0001'" class="control" name="weight_from" value="{{ old('weight_from') ? old('weight_from') : $shippingRate->weight_from }}" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.weight-from') }}&quot;">
                                <span class="control-error" v-if="errors.has('weight_from')">@{{ errors.first('weight_from') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('weight_to') ? 'has-error' : '']">
                                <label for="weight_to" class="required">{{ __('tablerate::app.admin.shipping-rates.weight-to') }}</label>
                                <input type="text" v-validate="'required:decimal:4|min_value:0.0001'" class="control" name="weight_to" value="{{ old('weight_to') ? old('weight_to') : $shippingRate->weight_to }}" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.weight-to') }}&quot;">
                                <span class="control-error" v-if="errors.has('weight_to')">@{{ errors.first('weight_to') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('zip_from') ? 'has-error' : '']">
                                <label for="zip_from" class="required">{{ __('tablerate::app.admin.shipping-rates.zip-from') }}</label>
                                <input type="text" v-validate="'required'" class="control" name="zip_from" value="{{ old('zip_from') ? old('zip_from') : $shippingRate->zip_from }}" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.zip-from') }}&quot;">
                                <span class="control-error" v-if="errors.has('zip_from')">@{{ errors.first('zip_from') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('zip_to') ? 'has-error' : '']">
                                <label for="zip_to" class="required">{{ __('tablerate::app.admin.shipping-rates.zip-to') }}</label>
                                <input type="text" v-validate="'required'" class="control" name="zip_to" value="{{ old('zip_to') ? old('zip_to') : $shippingRate->zip_to }}" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.zip-to') }}&quot;">
                                <span class="control-error" v-if="errors.has('zip_to')">@{{ errors.first('zip_to') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('price') ? 'has-error' : '']">
                                <label for="price" class="required">{{ __('tablerate::app.admin.shipping-rates.price') }}</label>
                                <input type="text" v-validate="'required|decimal:4|min_value:0'" class="control" name="price" value="{{ old('price') ? old('price') : $shippingRate->price }}" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.price') }}&quot;">
                                <span class="control-error" v-if="errors.has('price')">@{{ errors.first('price') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('is_zip_range') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('tablerate::app.admin.shipping-rates.is-range') }}</label>

                                <select class="control" v-validate="'required'" id="is_zip_range" name="is_zip_range" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.is_zip_range') }}&quot;">
                                    <option value="0" @if ( (old('is_zip_range') ? $shippingRate->is_zip_range : $shippingRate->is_zip_range) == 1) selected @endif>{{ __('tablerate::app.admin.shipping-rates.isrange-yes') }}</option>
                                    <option value="1" @if ( (old('is_zip_range') ? $shippingRate->is_zip_range : $shippingRate->is_zip_range) == 0) selected @endif>{{ __('tablerate::app.admin.shipping-rates.isrange-no') }}</option>
                                </select>
                                
                                <span class="control-error" v-if="errors.has('is_zip_range')">@{{ errors.first('is_zip_range') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('zip_code') ? 'has-error' : '']">
                                <label for="text" class="required">{{ __('tablerate::app.admin.shipping-rates.zip-code') }}</label>
                                <input type="text" v-validate="'required'" class="control" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : ($shippingRate->zip_code ? $shippingRate->zip_code : '*') }}" data-vv-as="&quot;{{ __('tablerate::app.admin.shipping-rates.zip-code') }}&quot;">
                                <span class="control-error" v-if="errors.has('zip_code')">@{{ errors.first('zip_code') }}</span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop
