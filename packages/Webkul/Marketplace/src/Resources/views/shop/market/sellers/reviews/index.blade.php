@extends('marketplace::shop.layouts.master')


@section('page_title')
    {{ __('marketplace::app.shop.sellers.reviews.title', ['shop_title' => $seller->shop_title]) }} - {{ $seller->name }}
@endsection

@section('content-wrapper')
    <section>
        {!! view_render_event('marketplace.shop.sellers.reviews.index.before', ['seller' => $seller]) !!}

        @include('marketplace::shop.market.sellers.left-profile')
        <div class="container my-5">
        <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>
            <div class="row">
                <div class="col-12 col-sm"><h2>{{ __('shop::app.reviews.rating-reviews') }}</h2></div>
                <div class="col-12 col-sm-auto text-right">
                    <a href="{{ route('marketplace.reviews.create', $seller->url) }}" class="btn btn-primary">
                        <i class="far fa-star"></i><span>{{ __('marketplace::app.shop.sellers.reviews.write-review') }}</span>
                    </a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-4 mb-4">
                    <div class="row mb-4">
                        <div class="col-auto pr-0">
                            <h2 class="text-info">{{ $reviewRepository->getAverageRating($seller) }}</h2>
                        </div>
                        <div class="col">
                        <star-rating
                                ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                                push-class="mr5"
                                ></star-rating>
                                {{
                                    __('marketplace::app.shop.sellers.reviews.total-rating', [
                                            'total_rating' => $reviewRepository->getTotalRating($seller),
                                            'total_reviews' => $reviewRepository->getTotalReviews($seller),
                                        ])
                                }}
                        </div>
                    </div>
                    <div class="ratings-reviews">
                    @foreach ($reviewRepository->getPercentageRating($seller) as $key => $count)

                        <div class="rater 5star">
                            <div class="rate-number" id={{$key}}star></div>
                            <div class="star-name">Star</div>

                            <div class="line-bar">
                                <div style="background-color: black;" class="line-value" id="{{ $key }}"></div>
                            </div>

                            <div class="percentage">
                                <span> {{$count}}% </span>
                            </div>
                        </div>

                    @endforeach
                    </div>
                </div>
                <div class="col-12 col-md-7 offset-md-1">
                    <h3 class="mb-4">Reviews</h3>
                    <div class="reviews">
                        <?php
                            $page = request()->get('pages') ?? 1;

                            $reviews = $reviewRepository->getReviews($seller)->paginate(10 * $page);
                        ?>
                        @if ($reviews->count() > 0)
                            @foreach ($reviews as $review)
                                <div class="review">
                                    <div class="title">
                                        {{ $review->title }}
                                    </div>
                                    <star-rating
                                    ratings="{{ $review->rating }}"
                                    push-class="mr5"
                                    ></star-rating>
                                    <div class="message">
                                        {{ $review->comment }}
                                    </div>
                                    <div class="reviewer-details">
                                        <span class="by">
                                            {{
                                                __('marketplace::app.shop.sellers.reviews.by-user-date', [
                                                        'name' => $review->customer->name,
                                                        'date' => core()->formatDate($review->created_at, 'F d, Y')
                                                    ])
                                            }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>{{ __('marketplace::app.shop.sellers.reviews.no-reviews-yet') }}</p>
                        @endif
                    </div>
                    @if ($page < $reviews->lastPage())
                        <div class="navigation">
                            <a href="?pages={{ $page + 1 }}">
                                {{ __('marketplace::app.shop.sellers.reviews.view-more') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {!! view_render_event('marketplace.shop.sellers.reviews.index.after', ['seller' => $seller]) !!}

    </section>

@endsection

@push('scripts')

    <script>

        window.onload = (function() {
            var percentage = {};
            <?php foreach ($reviewRepository->getPercentageRating($seller) as $key => $count) { ?>

                percentage = <?php echo "'$count';"; ?>
                id = <?php echo "'$key';"; ?>
                idNumber = id + 'star';

                document.getElementById(id).style.width = percentage + "%";
                document.getElementById(id).style.height = 4 + "px";
                document.getElementById(idNumber).innerHTML = id ;

            <?php } ?>
        })();

    </script>

@endpush