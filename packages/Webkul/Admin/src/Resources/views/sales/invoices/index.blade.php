@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.invoices.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.sales.invoices.title') }}</h3>
            </div>

            <div class="page-action">
                <a class="btn btn-black" @click="showModal('downloadDataGrid')">
                <i class="far fa-file-export"></i>
                        {{ __('admin::app.export.export') }}
</a>
            </div>
        </div>

        <div class="page-content">
            @inject('orderInvoicesGrid', 'Webkul\Admin\DataGrids\OrderInvoicesDataGrid')
            {!! $orderInvoicesGrid->render() !!}
        </div>
    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>

@stop

@push('scripts')
    @include('admin::export.export', ['gridName' => $orderInvoicesGrid])
@endpush