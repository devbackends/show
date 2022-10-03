@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotions.cart-rules.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h3>{{ __('admin::app.promotions.cart-rules.title') }}</h3>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.cart-rules.create') }}" class="btn btn-primary">
                    {{ __('admin::app.promotions.cart-rules.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('cartRuleGrid','Webkul\Admin\DataGrids\CartRuleDataGrid')
            {!! $cartRuleGrid->render() !!}
        </div>
    </div>
@endsection