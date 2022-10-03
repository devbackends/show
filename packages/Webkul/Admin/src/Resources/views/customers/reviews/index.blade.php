@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.reviews.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.customers.reviews.title') }}</h3>
            </div>
            <div class="page-action">
                {{--  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    {{ __('Add Customer') }}
                </a>  --}}
            </div>
        </div>

        <div class="page-content">
            @inject('review','Webkul\Admin\DataGrids\CustomerReviewDataGrid')
            {!! $review->render() !!}
        </div>
    </div>

@stop