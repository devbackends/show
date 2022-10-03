@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('page-detail-wrapper')
<div class="settings-page">
    <div class="settings-page__header">
        <div class="settings-page__header-title">
            <p>{{ __('shop::app.customer.account.address.index.title') }}</p>
        </div>
        <div class="settings-page__header-actions">
            @if ($addresses->isEmpty())
            <a href="{{ route('customer.address.create') }}" class="btn btn-outline-gray"><i class="far fa-map-marker-alt"></i><span>{{ __('shop::app.customer.account.address.index.add') }}</span></a>
            @endif
            @if (! $addresses->isEmpty())
            <a href="{{ route('customer.address.create') }}" class="btn btn-outline-gray"><i class="far fa-map-marker-alt"></i><span>{{ __('shop::app.customer.account.address.index.add') }}</span></a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.address.list.before', ['addresses' => $addresses]) !!}
    <div class="settings-page__body">
        @if ($addresses->isEmpty())
        <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
        @else
        <div class="address-holder row mx-n2">
        @foreach ($addresses as $address)
            <div class="col-12 col-md-6 col-lg-4 px-2 mb-3">
                <div class="card card--address h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $address->first_name }} {{ $address->last_name }}</h5>
                        <p>{{ $address->address1 }},{{ $address->city }},{{ $address->state }},{{ core()->country_name($address->country) }} {{ $address->postcode }},{{$address->phone }}</p>
                        <a href="{{ route('customer.address.edit', $address->id) }}" class="card-link">
                            <i class="far fa-edit"></i>
                            <span>{{ __('shop::app.customer.account.address.index.edit') }}</span>
                        </a>
                        <a href="{{ route('address.delete', $address->id) }}" class="card-link" onclick="deleteAddress('{{ __('shop::app.customer.account.address.index.confirm-delete') }}')">
                            <i class="far fa-trash-alt"></i>
                            <span>{{ __('shop::app.customer.account.address.index.delete') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    {!! view_render_event('bagisto.shop.customers.account.address.list.after', ['addresses' => $addresses]) !!}
</div>



@endsection

@push('scripts')
<script>
    function deleteAddress(message) {
        if (!confirm(message))
            event.preventDefault();
    }
</script>
@endpush