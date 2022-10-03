@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.address.index.page-title') }}
@endsection

@section('page-detail-wrapper')
@if ($addresses->isEmpty())
<a href="{{ route('customer.address.create') }}" class="theme-btn light unset pull-right">
    {{ __('shop::app.customer.account.address.index.add') }}
</a>
@endif

<div class="customer-profile__content-wrapper">
    <div class="customer-profile__content-header d-flex">
        <a href="{{ route('customer.account.index') }}" class="customer-profile__content-header-back">
            <i class="far fa-chevron-left"></i>
        </a>
        <h3 class="customer-profile__content-header-title">
            {{ __('shop::app.customer.account.address.index.title') }}
        </h3>

        @if (! $addresses->isEmpty())
        <div class="customer-profile__content-header-actions ml-auto">
            <a href="{{ route('customer.address.create') }}" class="btn btn-outline-dark">
                <i class="far fa-map-marker-plus"></i>Add address
            </a>
        </div>
        @endif

    </div>

    {!! view_render_event('bagisto.shop.customers.account.address.list.before', ['addresses' => $addresses]) !!}

    <div class="account-table-content">
        @if ($addresses->isEmpty())
        <div>{{ __('shop::app.customer.account.address.index.empty') }}</div>
        @else
        <div class="address-holder row">
            @foreach ($addresses as $address)
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title paragraph bold black">{{ $address->first_name }} {{ $address->last_name }}</h5>

                        <ul type="none">
                            {{-- <li>{{ $address->company_name }}</li> --}}
                            <li class="paragraph black">{{ $address->address1 }}, </li>
                            <li class="paragraph black">{{ $address->city }}, </li>
                            <li class="paragraph black">{{ $address->state }}, </li>
                            <li class="paragraph black">{{ core()->country_name($address->country) }} {{ $address->postcode }}, </li>
                            <li class="paragraph black">{{$address->phone }}</li>{{--{{ __('shop::app.customer.account.address.index.contact') }} :--}}
                        </ul>

                        <a class="card-link link-color" href="{{ route('customer.address.edit', $address->id) }}">
                            <span><i class="far fa-edit"></i></span>
                            <span>{{ __('shop::app.customer.account.address.index.edit') }}</span>
                        </a>

                        <a class="card-link link-color" href="{{ route('address.delete', $address->id) }}" onclick="deleteAddress('{{ __('shop::app.customer.account.address.index.confirm-delete') }}')">
                            <span><i class="far fa-trash-alt"></i></span>
                            <span> {{ __('shop::app.customer.account.address.index.delete') }}</span>
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