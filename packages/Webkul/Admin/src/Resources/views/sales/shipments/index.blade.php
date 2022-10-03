@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.shipments.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.sales.shipments.title') }}</h3>
            </div>

            <div class="page-action">
                <a class="btn btn-black" @click="showModal('downloadDataGrid')">
                <i class="far fa-file-export"></i>
                        {{ __('admin::app.export.export') }}
</a>
            </div>
        </div>

        <div class="page-content">
            @inject('orderShipmentsGrid', 'Webkul\Admin\DataGrids\OrderShipmentsDataGrid')
            {!! $orderShipmentsGrid->render() !!}
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
    @include('admin::export.export', ['gridName' => $orderShipmentsGrid])
@endpush