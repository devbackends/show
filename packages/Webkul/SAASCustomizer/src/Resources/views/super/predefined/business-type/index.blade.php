@extends('saas::super.layouts.content')

@section('page_title')
Predefined
@stop

@section('content')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>Business Type</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('super.predefined.business-type.add') }}" class="btn btn-primary">
                    Add Business Type
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('businessTypes', 'Webkul\SAASCustomizer\DataGrids\BusinessTypeDataGrid')
            {!! $businessTypes->render() !!}
        </div>
    </div>
@stop

@push('scripts')

@endpush