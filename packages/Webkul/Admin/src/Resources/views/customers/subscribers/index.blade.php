@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.subscribers.title') }}
@stop

@section('content')


    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.customers.subscribers.title') }}</h3>
            </div>

             <div class="page-action">
                <a href="{{ route('admin.customers.subscribers.create') }}" class="btn btn-primary">
                    Add Subscriber
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('subscribers','Webkul\Admin\DataGrids\NewsLetterDataGrid')
            {!! $subscribers->render() !!}
        </div>
    </div>
@stop