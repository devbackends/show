@extends('marketplace::admin.layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.user-help-requests.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('marketplace::app.admin.user-help-requests.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\Marketplace\DataGrids\Admin\UserHelpRequestDataGrid')->render() !!}

        </div>
    </div>

@stop
