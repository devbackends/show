@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.users.roles.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.users.roles.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    {{ __('Add Role') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('roles','Webkul\Admin\DataGrids\RolesDataGrid')
            {!! $roles->render() !!}
        </div>
    </div>
@stop
