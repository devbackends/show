@extends('admin::layouts.content')

@section('page_title')
    {{ __('tablerate::app.admin.superset-rates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('tablerate::app.admin.superset-rates.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.tablerate.superset_rates.create') }}" class="btn btn-primary">

                    {{ __('tablerate::app.admin.superset-rates.add-btn-title') }}

                </a>
            </div>
        </div>

        <div class="page-content">
            {!! app('Webkul\TableRate\DataGrids\Admin\SuperSetRateDataGrid')->render() !!}
        </div>
    </div>
@stop