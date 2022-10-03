@if(Request::route()->getName()=='marketplace.seller.show')
    @php  $showContactSeller=''; @endphp
@else
    @php  $showContactSeller='hide'; @endphp
@endif

<div class=" seller-section mb-5">
    @if(!empty($seller->banner))
        @php $banner=getenv('WASSABI_STORAGE').'/'.$seller->banner; @endphp
    @else
        @php $banner=bagisto_asset('images/seller-default-banner.jpg') @endphp
    @endif
    <div class="seller-section__promo" style="background-image: url(@if(!empty($seller->banner)) {{ $banner}}  @else {{ bagisto_asset('images/seller-default-banner.jpg') }} @endif);"></div>
    <div class="container">
        <div class="seller-section__info">
            <div class="logo">
                @if ($logo = $seller->logo_url)
                    <img src="{{ $logo }}" />
                @else
                    <img src="{{ bagisto_asset('images/seller-default-logo.png') }}" />
                @endif
            </div>
            <div class="seller-section__info-inner">
                <div class="seller-section__head">
                    <h2 class="h3 mb-0">{{ $seller->shop_title }}</h2>
                </div>
                <div class="rate">
                    <?php $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository') ?>

                    <?php $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository'); ?>
                    {{ $reviewRepository->getAverageRating($seller) }}
                    <star-rating
                                ratings="{{ ceil($reviewRepository->getAverageRating($seller)) }}"
                                push-class="mr5"
                                ></star-rating>
                    <a href="{{ route('marketplace.reviews.index', $seller->url) }}">
                        {{
                        __('marketplace::app.shop.sellers.profile.total-rating', [
                                'total_rating' => $reviewRepository->getTotalRating($seller),
                                'total_reviews' => $reviewRepository->getTotalReviews($seller),
                            ])
                    }}
                    </a>


                </div>
                @if ($seller->country)
                <div class="location box-location">
                    <i class="far fa-map-marker-alt"></i>
                    <div class="box-location__inner">
                        <p class="font-weight-bold">{{ $seller->city . ', '. $seller->state . ' (' . core()->country_name($seller->country) . ')' }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="action">
<!--                 <a href="{{ route('marketplace.seller.show', $seller->url) }}" class="btn btn-outline-primary">
                    <i class="fal fa-tags"></i>
                    <span>See {{ __('marketplace::app.shop.sellers.profile.count-products', [
                                            'count' => $productFlatRepository->getTotalProducts($seller)
                                        ])
                                    }} from this seller</span>
                </a> -->
                <a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#contactSeller">
                    <i class="fal fa-comment-alt-lines"></i>
                    <span>Contact seller</span>
                </a>
            </div>
        </div>
    </div>
</div>