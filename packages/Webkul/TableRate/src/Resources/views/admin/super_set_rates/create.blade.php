@extends('admin::layouts.content')

@section('page_title')
    {{ __('tablerate::app.admin.superset-rates.add-rate-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tablerate.superset_rates.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            @csrf
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/tablerate/supersets') }}';"></i>

                        {{ __('tablerate::app.admin.superset-rates.add-rate-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-primary">
                        {{ __('tablerate::app.admin.superset-rates.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <accordian :title="'{{ __('tablerate::app.admin.superset-rates.details') }}'" :active="true">
                        <div slot="body">
                            @csrf()

                            <div class="control-group" :class="[errors.has('price_from') ? 'has-error' : '']">
                                <label for="price_from" class="required">{{ __('tablerate::app.admin.superset-rates.price-from') }}</label>
                                <input type="text" v-validate="'required|decimal:4'" class="control" name="price_from" value="{{ old('price_from') }}" data-vv-as="&quot;{{ __('tablerate::app.admin.superset-rates.price-from') }}&quot;">
                                <span class="control-error" v-if="errors.has('price_from')">@{{ errors.first('price_from') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('price_to') ? 'has-error' : '']">
                                <label for="price_to" class="required">{{ __('tablerate::app.admin.superset-rates.price-to') }}</label>
                                <input type="text" v-validate="'required|decimal:4'" class="control" name="price_to" value="{{ old('price_to') }}" data-vv-as="&quot;{{ __('tablerate::app.admin.superset-rates.price-to') }}&quot;">
                                <span class="control-error" v-if="errors.has('price_to')">@{{ errors.first('price_to') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('shipping_type') ? 'has-error' : '']">
                                <label for="shipping_type" class="required">{{ __('tablerate::app.admin.superset-rates.shipping-type') }}</label>

                                <select class="control" v-validate="'required'" id="shipping_type" name="shipping_type" data-vv-as="&quot;{{ __('tablerate::app.admin.superset-rates.shipping-type') }}&quot;">
                                    <option value="Fixed" {{ old('shipping_type') == 'Fixed' ? 'selected' : '' }}>{{ __('tablerate::app.admin.superset-rates.fixed') }}</option>
                                    <option value="Free" {{ old('shipping_type') == 'Free' ? 'selected' : '' }}>{{ __('tablerate::app.admin.superset-rates.free') }}</option>
                                </select>
                                
                                <span class="control-error" v-if="errors.has('shipping_type')">@{{ errors.first('shipping_type') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('tablerate_superset_id') ? 'has-error' : '']">
                                <label for="tablerate_superset_id" class="required">{{ __('tablerate::app.admin.superset-rates.superset-name') }}</label>
                                <select class="control" v-validate="'required'" id="" name="tablerate_superset_id" data-vv-as="&quot;{{ __('tablerate::app.admin.superset-rates.superset-name') }}&quot;">
                                    <option value="">{{ __('tablerate::app.admin.superset-rates.select-superset') }}</option>
                                    @foreach($superSets as $super_set))
                                        <option value="{{ $super_set->id }}"  {{ old('tablerate_superset_id') == $super_set->id ? 'selected' : '' }}>{{ $super_set->name }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('tablerate_superset_id')">@{{ errors.first('tablerate_superset_id') }}</span>
                            </div>

                            <div class="control-group price" :class="[errors.has('price') ? 'has-error' : '']">
                                <label for="price" class="required">{{ __('tablerate::app.admin.superset-rates.price') }}</label>
                                <input type="text" v-validate="'required|decimal:4|min_value:0'" id="get_price" class="control" name="price" value="{{ old('price') }}" data-vv-as="&quot;{{ __('tablerate::app.admin.superset-rates.price') }}&quot;">
                                <span class="control-error" v-if="errors.has('price')">@{{ errors.first('price') }}</span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop