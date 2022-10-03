@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotions.catalog-rules.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.promotions.catalog-rules.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog-rules.create') }}" class="btn btn-primary">
                    {{ __('admin::app.promotions.catalog-rules.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('catalogRuleGrid','Webkul\Admin\DataGrids\CatalogRuleDataGrid')
            {!! $catalogRuleGrid->render() !!}
        </div>
    </div>
@endsection