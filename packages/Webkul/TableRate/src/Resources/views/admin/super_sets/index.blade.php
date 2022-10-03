@extends('admin::layouts.content')

@section('page_title')
    {{ __('tablerate::app.admin.supersets.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('tablerate::app.admin.supersets.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.tablerate.supersets.create') }}" class="btn btn-primary">
                    {{ __('tablerate::app.admin.supersets.add-btn-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            {!! app('Webkul\TableRate\DataGrids\Admin\SuperSetDataGrid')->render() !!}
        </div>
    </div>
@stop