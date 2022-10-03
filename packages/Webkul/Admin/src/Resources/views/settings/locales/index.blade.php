@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.locales.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.settings.locales.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.locales.create') }}" class="btn btn-primary">
                    {{ __('admin::app.settings.locales.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            @inject('locales','Webkul\Admin\DataGrids\LocalesDataGrid')
            {!! $locales->render() !!}
        </div>
    </div>
@stop