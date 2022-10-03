@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.settings.channels.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.channels.create') }}" class="btn btn-primary">
                    {{ __('admin::app.settings.channels.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('channels','Webkul\Admin\DataGrids\ChannelDataGrid')
            {!! $channels->render() !!}
        </div>
    </div>
@stop