@extends('saas::super.layouts.content')

@section('page_title')
Predefined
@stop

@section('content')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>MMC</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('super.predefined.mmc.add') }}" class="btn btn-primary">
                    Add MMC
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('mmc', 'Webkul\SAASCustomizer\DataGrids\MmcDataGrid')
            {!! $mmc->render() !!}
        </div>
    </div>
@stop

@push('scripts')

@endpush