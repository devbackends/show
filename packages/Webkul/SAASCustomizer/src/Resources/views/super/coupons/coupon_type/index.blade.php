@extends('saas::super.layouts.content')

@section('page_title')
Coupon Type
@stop

@section('content')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>Coupon Type</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('super.coupons-type.add') }}" class="btn btn-primary">
                    Add Coupon Type
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('couponType', 'Webkul\SAASCustomizer\DataGrids\CouponTypeDataGrid')
            {!! $couponType->render() !!}
        </div>
    </div>
@stop

@push('scripts')

@endpush