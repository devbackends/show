@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.families.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.catalog.families.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.families.create') }}" class="btn btn-primary">
                    {{ __('admin::app.catalog.families.add-family-btn-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.families.list.before') !!}

        <div class="page-content">

            {!! app('Webkul\Admin\DataGrids\AttributeFamilyDataGrid')->render() !!}

        </div>

        {!! view_render_event('bagisto.admin.catalog.families.list.after') !!}

    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            let productFamiliesItems = $('.parent_');

            productFamiliesItems.each(function (index, el) {
                let codeValue = $($($(el).children())[1]).text();

                if ((codeValue == 'default' || codeValue == 'Firearm' || codeValue == 'Ammunition')) {
                    $($($($($($(el).children())[3])).children()[0]).children()[1]).hide();
                }
            })
        });
    </script>
@endpush
