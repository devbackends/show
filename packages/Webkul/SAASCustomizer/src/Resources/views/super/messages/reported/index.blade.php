@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.configurations.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    Reported Messages
                </h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">

           <div>
               {!! app('Webkul\SAASCustomizer\DataGrids\ReportedMessagesDataGrid')->render() !!}
           </div>

        </div>
    </div>

@stop

@push('scripts')

@endpush