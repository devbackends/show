@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')

@php
if (! isset($total)) {
$total = $reviewHelper->getTotalReviews($product);

$avgRatings = $reviewHelper->getAverageRating($product);
$avgStarRating = ceil($avgRatings);
}

$percentageRatings = $reviewHelper->getPercentageRating($product);
$countRatings = $customHelper->getCountRating($product);
@endphp

{!! view_render_event('bagisto.shop.products.review.before', ['product' => $product]) !!}

@if ($total)
@if (isset($accordian) && $accordian)
<accordian :active="true">
    {{-- customer ratings --}}
    <div slot="header" class="col-lg-12 no-padding">
        <h3 class="display-inbl">
            {{ __('velocity::app.products.customer-rating') }}
        </h3>

        <i class="rango-arrow"></i>
    </div>

    <div class="row customer-rating" slot="body">
        <div class="row full-width text-center mb30">
            <div class="col-lg-12 col-xl-6">
                <h4 class="col-lg-12 fs16">{{ $avgRatings }} {{ __('shop::app.reviews.star') }}</h4>

                <star-ratings :size="24" :ratings="{{ $avgStarRating }}"></star-ratings>

                <div>
                    {{ __('shop::app.reviews.ratingreviews', [
                                    'rating' => $avgRatings,
                                    'review' => $total])
                                }}
                            </div>

                @if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
                <a class="btn btn-outline-primary" href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
                {{ __('velocity::app.products.write-your-review') }}
                </a>
                @endif
            </div>

            <div class="col-lg-12 col-xl-6">

                @for ($i = 5; $i >= 1; $i--)

                <div class="row">
                    <span class="col-3 no-padding fs16 fw6">{{ $i }} {{ __('shop::app.reviews.star') }}</span>

                    <div class="col-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                        <div style="width: {{ $percentageRatings[$i] }}%"></div>
                    </div>

                    <span class="col-2 fs16">{{ $countRatings[$i] }}</span>
                </div>
                @endfor

            </div>
        </div>
    </div>
</accordian>
@else
@if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
<div class="row">
    <div class="col">
    
        <a class="btn btn-outline-primary" href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
        {{ __('velocity::app.products.write-your-review') }}
        </a>
        
    </div>
</div>
@endif
<div class="customer-rating__product-total">
<div class="row customer-rating d-flex align-items-stretch">
    <!-- <h3 class="col-lg-12">{{ $avgRatings }} {{ __('shop::app.reviews.star') }}</h3> -->
    <div class="col-md-12 col-lg-6 text-center d-flex">
        <div class="align-self-center w-100">
        <star-ratings :size="24" :ratings="{{ $avgStarRating }}"></star-ratings>

        <div>
            {{ __('shop::app.reviews.ratingreviews', [
                                'rating' => $avgRatings,
                                'review' => $total])
                            }}
                        </div>
        </div>

    </div>

    <div class="col-md-12 col-lg-6 text-center">

        @for ($i = 5; $i >= 1; $i--)

        <div class="row">
            <span class="col-3 no-padding fs16 fw6">{{ $i }} Star</span>

            <div class="col-7 rating-bar" title="{{ $percentageRatings[$i] }}%">
                <div style="width: {{ $percentageRatings[$i] }}%"></div>
            </div>

            <span class="col-2 fs16">{{ $countRatings[$i] }}</span>
        </div>
        @endfor

    </div>
</div>
</div>
@endif

@if (isset($accordian) && $accordian)
<accordian :title="'{{ __('shop::app.products.total-reviews') }}'" :active="true">
    {{-- customer reviews --}}
    <div slot="header" class="col-lg-12 no-padding">
        <h3 class="display-inbl">
            {{ __('velocity::app.products.reviews-title') }}
        </h3>

        <i class="rango-arrow"></i>
    </div>

    <div class="customer-reviews" slot="body">
        @foreach ($reviewHelper->getReviews($product)->paginate(10) as $review)
        <div class="row customer-reviews__item">
            <h4 class="col-lg-12">{{ $review->title }}</h4>

            <star-ratings :ratings="{{ $review->rating }}" push-class="col-lg-12"></star-ratings>

            <div class="customer-reviews__description col-lg-12">
                <p>{{ $review->comment }}</p>
            </div>

            <div class="col-lg-12 customer-reviews__reviewed-by">
                <p>{{ __('velocity::app.products.review-by') }} -</p>

                <p class="">
                    {{ $review->name }},
                </p>

                <p>{{ core()->formatDate($review->created_at, 'F d, Y') }}</p>
            </div>
        </div>
        @endforeach

        <a href="{{ route('shop.reviews.index', ['slug' => $product->url_key ]) }}" class="mb20 link-color">{{ __('velocity::app.products.view-all-reviews') }}</a>
    </div>
</accordian>
@else
<h3 class="display-inbl mb20 col-lg-12 no-padding">
    {{ __('velocity::app.products.reviews-title') }}
</h3>

<div class="customer-reviews">
    @foreach ($reviewHelper->getReviews($product)->paginate(10) as $review)
    <div class="row customer-reviews__item">
        <h4 class="col-lg-12">{{ $review->title }}</h4>

        <star-ratings :ratings="{{ $review->rating }}" push-class="mr10 fs16 col-lg-12"></star-ratings>

        <div class="customer-reviews__description col-lg-12">
            <p>{{ $review->comment }}</p>
        </div>

        <div class="col-lg-12 customer-reviews__reviewed-by">
            @if ("{{ $review->name }}")
            <p>{{ __('velocity::app.products.review-by') }} -</p>

            <p>
                {{ $review->name }},
            </p>
            @endif

            <p>{{ core()->formatDate($review->created_at, 'F d, Y') }}
            </p>
        </div>
    </div>
    @endforeach
</div>
@endif

@else
@if (core()->getConfigData('catalog.products.review.guest_review') || auth()->guard('customer')->check())
<div class="customer-rating" style="border: none">
    <a class="btn btn-outline-primary" href="{{ route('shop.reviews.create', ['slug' => $product->url_key ]) }}">
    {{ __('velocity::app.products.write-your-review') }}
    </a>
</div>
@endif
@endif

{!! view_render_event('bagisto.shop.products.review.after', ['product' => $product]) !!}