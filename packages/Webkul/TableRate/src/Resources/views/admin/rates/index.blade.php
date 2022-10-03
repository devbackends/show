@extends('admin::layouts.content')

@section('page_title')
    {{ __('tablerate::app.admin.shipping-rates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('tablerate::app.admin.shipping-rates.title') }}</h1>
            </div>

            <div class="page-action">
                <div class="export-import" @click="showModal('upload_tablerate_shipping_rate')" style="margin-right: 20px;">
                    <i class="import-icon" style="transform: rotate(180deg);"></i>
                    <span>
                        {{ __('tablerate::app.admin.shipping-rates.import') }}
                    </span>
                </div>

                <div class="export-import" @click="showModal('download_tablerate_shipping_rate')">
                    <i class="export-icon"></i>
                    <span>
                        {{ __('tablerate::app.admin.shipping-rates.export') }}
                    </span>
                </div>

                <a href="{{ route('admin.tablerate.shipping_rates.create') }}" class="btn btn-primary btn-lg">
                    {{ __('tablerate::app.admin.shipping-rates.btn-add-rate') }}
                </a>
            </div>
        </div>

        <modal id="download_tablerate_shipping_rate" :is-open="modalIds.download_tablerate_shipping_rate">
            <h3 slot="header">{{ __('tablerate::app.admin.shipping-rates.download') }}</h3>

            <div slot="body">
                <export-form></export-form>
            </div>
        </modal>

        <modal id="upload_tablerate_shipping_rate" :is-open="modalIds.upload_tablerate_shipping_rate">

            <h3 slot="header">{{ __('tablerate::app.admin.shipping-rates.upload') }}</h3>

            <div slot="body">
                <form method="POST" action="{{ route('admin.tablerate.shipping_rates.import') }}" enctype="multipart/form-data" @submit.prevent="onSubmit">
                    @csrf()

                    <div class="control-group" :class="[errors.has('file') ? 'has-error' : '']">
                        <label for="file" class="required">{{ __('admin::app.export.file') }}</label>
                        <input v-validate="'required'" type="file" class="control" id="file" name="file" data-vv-as="&quot;
                        {{ __('admin::app.export.file') }}&quot;" value="{{ old('file') }}" style="padding-top: 5px">
                        <span>{{ __('tablerate::app.admin.shipping-rates.allowed-type') }}</span>
                        <span><b>{{ __('tablerate::app.admin.shipping-rates.file-type') }}</b></span>
                        <span class="control-error" v-if="errors.has('file')">@{{ errors.first('file') }}</span>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{__('tablerate::app.admin.shipping-rates.import') }}
                    </button>
                </form>

                <label for="fileupload">
                    <a href="{{ route('admin.tablerate.shipping_rates.sample_download') }}" class="locale" style="color: red; text-decoration: underline; padding-right: 68%; padding-bottom: 10px;">{{__('tablerate::app.admin.shipping-rates.download-file') }}</a>
                </label>
            </div>
        </modal>

        <div slot="body">
            <div class="page-content">

                {!! app('Webkul\TableRate\DataGrids\Admin\ShippingRateDataGrid')->render() !!}

                @inject('ShippingRateDataGrid', 'Webkul\TableRate\DataGrids\Admin\ShippingRateDataGrid')
            </div>
        </div>
    </div>
@stop

@push('scripts')

    @include('tablerate::export.export', ['gridName' => $ShippingRateDataGrid])

@endpush