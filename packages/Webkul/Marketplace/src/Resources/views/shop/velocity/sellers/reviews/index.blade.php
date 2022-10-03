@extends('marketplace::shop.layouts.master')


@section('page_title')
    {{ __('marketplace::app.shop.sellers.reviews.title', ['shop_title' => $seller->shop_title]) }} - {{ $seller->name }}
@endsection

@section('content-wrapper')
    <section class="profile-container review">
        {!! view_render_event('marketplace.shop.sellers.reviews.index.before', ['seller' => $seller]) !!}

        @include('marketplace::shop.velocity.sellers.left-profile')

        <div class="profile-right-block">
            <div class="review-form">

                <div class="heading mt-10">
                    <span> {{ __('shop::app.reviews.rating-reviews') }} </span>

                    <a href="{{ route('marketplace.reviews.create', $seller->url) }}" class="btn btn-lg theme-btn right">

                        {{ __('marketplace::app.shop.sellers.reviews.write-review') }}
                    </a>
                </div>

                <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

                <div class="ratings-reviews mt-35">

                    <div class="left-side">
                        <span class="rate">
                            {{ $reviewRepository->getAverageRating($seller) }}
                        </span>

                        <star-ratings
                        ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                        push-class="mr5"
                        ></star-ratings>

                        <div class="total-reviews mt-5">
                            {{
                                __('marketplace::app.shop.sellers.reviews.total-rating', [
                                        'total_rating' => $reviewRepository->getTotalRating($seller),
                                        'total_reviews' => $reviewRepository->getTotalReviews($seller),
                                    ])
                            }}
                        </div>
                    </div>

                    <div class="right-side">
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

                            <br/>

                        @endforeach
                    </div>

                </div>

                <div class="rating-reviews">
                    <div class="reviews">

                        <?php
                            $page = request()->get('pages') ?? 1;

                            $reviews = $reviewRepository->getReviews($seller)->paginate(10 * $page);
                        ?>

                        @foreach ($reviews as $review)

                            <div class="review">
                                <div class="title">
                                    {{ $review->title }}
                                </div>

                                <star-ratings
                                ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                                push-class="mr5"
                                ></star-ratings>

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