@extends('saas::super.layouts.content')

@section('page_title')
Coupon
@stop

@section('content')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>Coupon</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('super.coupon.add') }}" class="btn btn-primary">
                    Add Coupon
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('coupon', 'Webkul\SAASCustomizer\DataGrids\CouponDataGrid')
            {!! $coupon->render() !!}
        </div>
    </div>
@stop

@push('scripts')

@endpush