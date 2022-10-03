@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.sliders.title') }}
@stop

@section('content')


    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.settings.sliders.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.sliders.store') }}" class="btn btn-primary">
                    {{ __('admin::app.settings.sliders.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('sliders','Webkul\Admin\DataGrids\SliderDataGrid')
            {!! $sliders->render() !!}
        </div>
    </div>
@stop