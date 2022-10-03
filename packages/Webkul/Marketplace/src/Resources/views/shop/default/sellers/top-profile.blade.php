<div class="profile-top-block">

    <div class="profile-information">

        <div class="profile-logo-block">
            @if ($logo = $seller->logo_url)
                <img src="{{ $logo }}" />
            @else
                <img src="{{ bagisto_asset('images/default-logo.svg') }}" />
            @endif
        </div>

        <div class="profile-information-block">

            <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="shop-title">{{ $seller->shop_title }}</a>

            @if ($seller->country)
                <label class="shop-address">
                    {{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}
                </label>
            @endif
        </div>

    </div>

    <div class="review-information">

        <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

        <?php $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository') ?>

        <span class="number">
            {{ $reviewRepository->getAverageRating($seller) }}
        </span>

        <span class="stars">
            @for ($i = 1; $i <= $reviewRepository->getAverageRating($seller); $i++)

                <span class="icon star-icon"></span>

            @endfor
        </span>

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