@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.settings.title') }}
@endsection

@section('content')

<div class="settings-page store-settings">
    <form method="post" action="{{ route('marketplace.account.settings.store') }}" enctype="multipart/form-data">
        @csrf()
        <div class="settings-page__header">
            <div class="settings-page__header-title">
                <p>{{ __('marketplace::app.shop.sellers.account.settings.title') }}</p>
            </div>
            <div class="settings-page__header-actions">
                <button type="submit" class="btn btn-primary" id="main-submit-btn">
                    {{ __('marketplace::app.shop.sellers.account.profile.save-btn-title') }}
                </button>
            </div>
        </div>
        <div class="settings-page__body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="payments-tab" data-toggle="tab" href="#payments" role="tab" aria-controls="payments" aria-selected="true">Payments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="shipping-tab" data-toggle="tab" href="#shipping" role="tab" aria-controls="shipping" aria-selected="false">Shipping</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="webhooks-tab" data-toggle="tab" href="#webhooks" role="tab" aria-controls="webhooks" aria-selected="false">Webhooks</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="plan-tab" data-toggle="tab" href="#plan" role="tab" aria-controls="plan" aria-selected="false">Your Plan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="plan-tab" data-toggle="tab" href="#card" role="tab" aria-controls="plan" aria-selected="false">Your Card</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                    @include('shop::sellers.account.settings.payments')
                </div>
                <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                    @include('shop::sellers.account.settings.shipping')
                </div>
                <div class="tab-pane fade" id="webhooks" role="tabpanel" aria-labelledby="webhooks-tab">
                    @include('shop::sellers.account.settings.webhooks')
                </div>
                <div class="tab-pane fade" id="plan" role="tabpanel" aria-labelledby="plan-tab">
                    @include('shop::sellers.account.settings.plan')
                </div>
                <div class="tab-pane fade" id="card" role="tabpanel" aria-labelledby="card-tab">
                    @include('shop::sellers.account.settings.card')
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
@push('scripts')
<script>

</script>
@endpush