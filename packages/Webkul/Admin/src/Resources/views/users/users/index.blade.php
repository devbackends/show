@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.users.users.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.users.users.title') }}</h3>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    {{ __('Add User') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            @inject('datagrid','Webkul\Admin\DataGrids\UserDataGrid')
            {!! $datagrid->render() !!}
            {{-- <datetime></datetime> --}}
        </div>
    </div>

@stop
