<div class="profile-top-block mb15">
    <div class="profile-information padding-15">

        @if ($logo = $seller->logo_url)
            <img src="{{ $logo }}" />
        @else
            <img src="{{ bagisto_asset('images/default-velocity-logo.png') }}" />
        @endif

        <div class="profile-information-block">

            <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="shop-title">{{ $seller->shop_title }}</a>

            @if ($seller->country)
                <label class="shop-address">
                    {{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}
                </label>
            @endif
        </div>

    </div>

    <div class="review-information padding-15">

        <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

        <?php $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository') ?>

        <span class="number">
            {{ $reviewRepository->getAverageRating($seller) }}
        </span>

        <div class="star-rating">
            <star-ratings
                ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                push-class="mr5"
            ></star-ratings>
        </div>

        <div class="total-reviews">
            <a href="{{ route('marketplace.reviews.index', $seller->url) }}">
                {{
                    __('marketplace::app.shop.sellers.profile.total-rating', [
                            'total_rating' => $reviewRepository->getTotalRating($seller),
                            'total_reviews' => $reviewRepository->getTotalReviews($seller),
                        ])
                }}
            </a>
        </div>

    </div>

</div>