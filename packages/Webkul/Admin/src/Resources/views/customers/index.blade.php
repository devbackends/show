@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.customers.customers.title') }}</h3>
            </div>
            <div class="page-action">
                <a class="btn btn-black" @click="showModal('downloadDataGrid')">
                <i class="far fa-file-export"></i>
                        {{ __('admin::app.export.export') }}
</a>

                <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">
                    {{ __('admin::app.customers.customers.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('customerGrid','Webkul\Admin\DataGrids\CustomerDataGrid')

            {!! $customerGrid->render() !!}
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
    @include('admin::export.export', ['gridName' => $customerGrid])
@endpush

