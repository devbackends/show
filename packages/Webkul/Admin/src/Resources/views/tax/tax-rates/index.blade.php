@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-rates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.settings.tax-rates.title') }}</h3>
            </div>

            <div class="page-action">
                <a class="btn btn-black" @click="showModal('uploadDataGrid')" style="margin-right: 20px;">
                    <i class="far fa-file-import"></i>
                        {{ __('admin::app.export.import') }}
</a>
                <a class="btn btn-black" @click="showModal('downloadDataGrid')">
                <i class="far fa-file-export"></i>
                        {{ __('admin::app.export.export') }}
</a>

                <a href="{{ route('admin.tax-rates.show') }}" class="btn btn-primary">
                    {{ __('admin::app.settings.tax-rates.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('taxRateGrid', 'Webkul\Admin\DataGrids\TaxRateDataGrid')
            {!! $taxRateGrid->render() !!}
        </div>
    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>

    <modal id="uploadDataGrid" :is-open="modalIds.uploadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.upload') }}</h3>
        <div slot="body">

            <form method="POST" action="{{ route('admin.tax-rates.import') }}" enctype="multipart/form-data" @submit.prevent="onSubmit">
                @csrf()
                <div class="control-group" :class="[errors.has('file') ? 'has-error' : '']">
                    <label for="file" class="required">{{ __('admin::app.export.file') }}</label>
                    <input v-validate="'required'" type="file" class="control" id="file" name="file" data-vv-as="&quot;{{ __('admin::app.export.file') }}&quot;" value="{{ old('file') }}"/ style="padding-top: 5px">
                    <span>{{ __('admin::app.export.allowed-type') }}</span>
                    <span><b>{{ __('admin::app.export.file-type') }}</b></span>
                    <span class="control-error" v-if="errors.has('file')">@{{ errors.first('file') }}</span>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('admin::app.export.import') }}
                </button>
            </form>

        </div>
    </modal>

@endsection

@push('scripts')
    @include('admin::export.export', ['gridName' => $taxRateGrid])
@endpush