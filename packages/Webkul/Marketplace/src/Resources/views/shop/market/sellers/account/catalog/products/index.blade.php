@extends('marketplace::shop.layouts.account')

@php

$alert = false;
if (session()->has('seller-alert')) {
    $alert = session()->get('seller-alert');
    session()->forget('seller-alert');
}

@endphp

@section('page_title')
{{ __('marketplace::app.shop.sellers.account.catalog.products.title') }}
@endsection

@section('content')

<div class="settings-page">
    <div class="settings-page__header">
        <div class="settings-page__header-title">
            <p>{{ __('marketplace::app.shop.sellers.account.catalog.products.title') }}</p>
        </div>
        <div class="settings-page__header-actions">
            <a href="{{ route('marketplace.account.products.create') }}" class="btn btn-primary">
                {{ __('marketplace::app.shop.sellers.account.catalog.products.create') }}
            </a>
        </div>
    </div>

    {!! view_render_event('marketplace.sellers.account.catalog.products.list.before') !!}

    <div class="settings-page__body settings-page__body-products">
        {!! app('Webkul\Marketplace\DataGrids\Shop\ProductDataGrid')->render() !!}
    </div>

    {!! view_render_event('marketplace.sellers.account.catalog.products.list.after') !!}

    <div>

    <!-- MODAL Delete product confirmation  -->
    <div class="modal normal fade" id="modalDeleteConfirmation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-head">
                        <i class="far fa-exclamation-triangle"></i>
                        <h3 class="mb-3">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-delete-title') }}</h3>
                        <p></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-primary" style="min-width: 90px; justify-content: center;" data-toggle="modal" data-target="#createNow" data-dismiss="modal">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-delete-cancel') }}</button>
                        <a href="" class="btn btn-primary" style="min-width: 90px; justify-content: center;">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-delete-confirm') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL Delete product confirmation -->

    <!-- MODAL MassDelete products confirmation  -->
    <div class="modal normal fade" id="modalMassDeleteConfirmation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-head">
                        <i class="far fa-exclamation-triangle"></i>
                        <h3 class="mb-3">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-mass-delete-title') }}</h3>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-primary" style="min-width: 90px; justify-content: center;" data-toggle="modal" data-target="#createNow" data-dismiss="modal">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-delete-cancel') }}</button>
                        <a id="massDeleteConfirm" class="btn btn-primary" style="min-width: 90px; justify-content: center;">{{ __('marketplace::app.shop.sellers.account.catalog.products.modal-delete-confirm') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL MassDelete products confirmation -->
</div>

</div>


@endsection

@push('scripts')
    <script>
        function onMassDeleteFormSubmit() {
            $('#modalMassDeleteConfirmation').modal('show');
        }
        $(document).ready(() => {
            const modal = $('#modalDeleteConfirmation');
            const deleteLink = '{{route('marketplace.account.products.delete', 777)}}'
            $('.delete-product').click(function (e) {
                e.preventDefault();
                let productName = $(this).parent().parent().siblings('[data-value="Name"]').text()
                let id = $(this).parent().parent().siblings('[data-value="Id"]').text()
                let productDeleteLink = deleteLink.replace('777', id);
                modal.find('.modal-head p').text(productName);
                modal.find('.modal-body .justify-content-between a').attr('href', productDeleteLink);
                modal.modal('show');
            })
            $('#massDeleteConfirm').click(function () {
                $('#mass-action-form').attr('onsubmit', '').submit();
                $('#modalMassDeleteConfirmation').modal('hide');
            })
        })
    </script>
@endpush

@if($alert)
    @push('scripts')
        <script>
            $(document).ready(() => {
                let alertId = Math.floor(Math.random() * 1000);
                let html = `<div class="alert alert-danger alert-dismissible" id="${alertId}">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Warning! </strong><br>{{$alert}}.
                        </div>`;

                $('#alert-container').append(html).ready(() => {
                    window.setTimeout(() => {
                        $(`#alert-container #${alertId}`).remove();
                    }, 5000);
                });
            })
        </script>
    @endpush
@endif