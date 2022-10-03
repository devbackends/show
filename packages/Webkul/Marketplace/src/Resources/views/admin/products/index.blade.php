@extends('marketplace::admin.layouts.content')

@section('page_title')
    {{ __('marketplace::app.admin.products.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('marketplace::app.admin.products.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\Marketplace\DataGrids\Admin\ProductDataGrid')->render() !!}

        </div>
    </div>

@stop
