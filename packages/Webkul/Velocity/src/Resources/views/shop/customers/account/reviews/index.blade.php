@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::customers.account.index')

@section('page_title')
{{ __('shop::app.customer.account.review.index.page-title') }}
@endsection

@section('page-detail-wrapper')
<div class="customer-profile__content-wrapper">
    <div class="customer-profile__content-header d-flex">
        <a href="{{ route('customer.account.index') }}" class="customer-profile__content-header-back">
            <i class="far fa-chevron-left"></i>
        </a>
        <h3 class="customer-profile__content-header-title">
            {{ __('shop::app.customer.account.review.index.title') }}
        </h3>

        @if (count($reviews) > 1)
        <div class="customer-profile__content-header-actions ml-auto">
            <a href="{{ route('customer.review.deleteall') }}" class="btn btn-outline-dark">
                <i class="far fa-trash-alt"></i>Delete all
            </a>
        </div>
        @endif

    </div>

    {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

    <div class="reviews-container">
        @if (! $reviews->isEmpty())
        @foreach ($reviews as $review)
        <div class="row col-12 fs16">
            <div class="col-12 row">
                @php
                $image = $productImageHelper->getProductBaseImage($review->product);
                @endphp

                <a href="{{ url()->to('/').'/'.$review->product->url_key }}" title="{{ $review->product->name }}" class="col-2 max-sm-img-dimention no-padding">
                    <img class="media" src="{{ $image['small_image_url'] }}" />
                </a>

                <div class="col-8">
                    <div class="product-name">
                        <a class="remove-decoration link-color" href="{{ url()->to('/').'/'.$review->product->url_key }}" title="{{ $review->product->name }}">
                            {{$review->product->name}}
                        </a>
                    </div>

                    <star-ratings class="black" ratings="{{ $review->rating }}"></star-ratings>

                    <h2 class="paragraph bold black">{{ $review->title }}</h2>

                    <p class="paragraph black">{{ $review->comment }}</p>
                </div>

                <div class="col-2">
                    <a class="unset" href="{{ route('customer.review.delete', $review->id) }}">
                        <span><i class="far fa-trash-alt"></i>
                            <span class="align-vertical-top paragraph bold">{{ __('shop::app.checkout.cart.remove') }}</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <div class="bottom-toolbar">
            {{ $reviews->links()  }}
        </div>
        {{-- <load-more-btn></load-more-btn> --}}
        @else
        <div class="fs16">
            {{ __('customer::app.reviews.empty') }}
        </div>
        @endif

    </div>

    {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}

</div>

@endsection

@push('scripts')
<script type="text/x-template" id="load-more-template">
    <div class="col-12 row justify-content-center">
            <button type="button" class="btn btn-outline-primary" @click="loadNextPage">Load More</button>
        </div>
    </script>

<script type="text/javascript">
    (() => {
        Vue.component('load-more-btn', {
            template: '#load-more-template',

            methods: {
                'loadNextPage': function() {
                    let splitedParamsObject = {};

                    let searchedString = window.location.search;
                    searchedString = searchedString.replace('?', '');

                    let splitedParams = searchedString.split('&');

                    splitedParams.forEach(value => {
                        let splitedValue = value.split('=');
                        splitedParamsObject[splitedValue[0]] = splitedValue[1];
                    });

                    splitedParamsObject[page]
                }
            }
        })
    })()
</script>
@endpush