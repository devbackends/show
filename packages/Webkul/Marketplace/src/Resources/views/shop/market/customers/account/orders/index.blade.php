@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')

<div class="settings-page">
    <div class="settings-page__header">
        <div class="settings-page__header-title">
            <p>{{ __('shop::app.customer.account.order.index.title') }}</p>
        </div>
        <div class="settings-page__header-actions"></div>
    </div>
    <div class="settings-page__body">

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        {!! app('Webkul\Shop\DataGrids\OrderDataGrid')->render() !!}

    </div>
</div>


@include('marketplace::shop.customers.account.orders.contact-seller')


{!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@push('scripts')
    <script>
        $(document).ready(() => {

            $('a[data-method="MESSAGE"]').click(function (e) {
                e.preventDefault();
                const contactSellerModal = $('#contactSeller');
                contactSellerModal.modal('show');
                const customersellerid = $(this).attr('data-customersellerid');
                $('#from').val(<?= auth()->guard('customer')->user()->id ?>);
                $('#to').val(customersellerid);
                $('#order_id').val($(this).attr('data-id'));
            });
        })
    </script>
@endpush
@endsection