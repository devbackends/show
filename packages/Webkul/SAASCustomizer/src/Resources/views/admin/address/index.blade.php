@extends('admin::layouts.content')

@section('page_title')
    {{ __('saas::app.admin.tenant.address-details') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.admin.tenant.address-details') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('company.address.create') }}" class="btn btn-primary">
                    {{ __('saas::app.admin.tenant.add-address') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            @inject('datagrid','Webkul\SAASCustomizer\DataGrids\AddressDataGrid')

            {!! $datagrid->render() !!}
        </div>
    </div>

@stop
