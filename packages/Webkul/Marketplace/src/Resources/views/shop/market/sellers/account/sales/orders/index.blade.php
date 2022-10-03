@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.sales.orders.title') }}
@endsection

@push('css')
    <style>
        @media print {
            .account-layout, .modal .modal-footer, .subscribe-section, #category-menu-header, .sidebar, .footer {display:none;}
            .modal {display:block;}
        }
    </style>
@endpush

@section('content')

    <div class="settings-page">

        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('marketplace::app.shop.sellers.account.sales.orders.title') }}</p>
            </div>
            <div class="settings-page__header-actions"></div>
        </div>


        {!! view_render_event('marketplace.sellers.account.sales.orders.list.before') !!}

        <div class="settings-page__body">
                {!! app('Webkul\Marketplace\DataGrids\Shop\OrderDataGrid')->render() !!}
        </div>

        {!! view_render_event('marketplace.sellers.account.sales.orders.list.after') !!}
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="order-shipping-label">
                        <div class="order-shipping-label__box">
                            <div class="order-shipping-label__from px-4 py-3">
                                <p class="font-weight-bold mb-0">From:</p>
                                <p class="mb-0 from-p"></p>
                            </div>
                            <div class="order-shipping-label__to p-4">
                                <div class="row">
                                    <div class="col-auto pr-0">
                                        <p class="font-paragraph-big-bold">Ship to:</p>
                                    </div>
                                    <div class="col">
                                        <p class="font-paragraph-big mb-0 to-p"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="javascript:window.print()"><i class="far fa-print mr-2"></i>Print</button>
                </div>
            </div>
        </div>
    </div>
    @include('marketplace::shop.customers.account.orders.contact-seller')
@endsection

@push('scripts')
    <script>
        $(document).ready(() => {

            $('a[data-method="CUSTOM"]').click(function (e) {
                const modal = $('#exampleModal');
                const url = '{{ route('marketplace.account.orders.get', 777) }}'
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    method: 'GET',
                    url: url.replace('777', id),
                }).done(res => {
                    const seller = res.order.seller;
                    const buyer = res.order.order.shipping_address;
                    modal.find('.from-p').html(`
                        ${seller.shop_title}<br>${seller.address1}<br>
                        ${seller.city}, ${seller.state} ${seller.postcode}<br>Contact: ${seller.phone}
                    `);
                    modal.find('.to-p').html(`
                        ${buyer.first_name} ${buyer.last_name}<br>${buyer.address1}<br>
                        ${buyer.city}, ${buyer.state} ${buyer.postcode}<br>Contact: ${buyer.phone}
                    `);
                    modal.modal('show');
                });
            })
            $('a[data-method="MESSAGE"]').click(function (e) {
                e.preventDefault();
                const contactSellerModal = $('#contactSeller');
                contactSellerModal.modal('show');
                const customrid = $(this).attr('data-customerid');
                $('#from').val(<?= auth()->guard('customer')->user()->id ?>);
                $('#to').val(customrid);
                $('#order_id').val($(this).attr('data-id'));
            });
        })
    </script>
@endpush
