<?php
$sellers = \Webkul\Marketplace\Models\Seller::query()
    ->where('is_approved', 1)
    ->whereNotNull('logo')
    ->whereIn('id', function ($q) {
        $q->select('marketplace_seller_id')->from('product_flat')->distinct();
    })->orderBy('id', 'desc')->get();
?>
<div class="new-sellers py-5">
    <div class="container">
        <p class="text-center h2">
            New sellers
        </p>
        <div class="new-sellers-slider__xs slider d-block d-sm-none">
            @foreach($sellers as $seller)
            @if(!empty($seller->shop_title) && !empty($seller->city))
            <div class="text-center">
                @if ($logo = $seller->logo_url)
                <img src="{{$logo}}" alt="" class="rounded-circle mx-auto mb-3" height="100px" width="100px">
                @else
                <img src="{{ asset('themes/market/assets/images/seller-default-logo.png') }}" height="100px" width="100px" class="rounded-circle mx-auto mb-3" />
                @endif

                <a href="{{ route('marketplace.seller.show', $seller->url) }}" class=""><p class="font-weight-bold mb-0">{{$seller->shop_title}}</p></a>
                <p>@if(!empty($seller->city)) {{$seller->city}}, @endif{{$seller->state}}</p>
            </div>
            @endif
            @endforeach
        </div>
        <div class="new-sellers-slider__md slider d-none d-sm-block d-lg-none">
            @foreach($sellers as $seller)
            @if(!empty($seller->shop_title) && !empty($seller->city))
            <div class="text-center">
                @if ($logo = $seller->logo_url)
                <img src="{{$logo}}" alt="" class="rounded-circle mx-auto mb-3" height="100px" width="100px">
                @else
                <img src="{{ asset('themes/market/assets/images/seller-default-logo.png') }}" height="100px" width="100px" class="rounded-circle mx-auto mb-3" />
                @endif

                <a href="{{ route('marketplace.seller.show', $seller->url) }}" class=""><p class="font-weight-bold mb-0">{{$seller->shop_title}}</p></a>
                <p>@if(!empty($seller->city)) {{$seller->city}}, @endif{{$seller->state}}</p>
            </div>
            @endif
            @endforeach
        </div>
        <div class="new-sellers-slider__lg slider d-none d-lg-block">
            @foreach($sellers as $seller)
            @if(!empty($seller->shop_title) && !empty($seller->city))
            <div class="text-center">
                @if ($logo = $seller->logo_url)
                <img src="{{$logo}}" alt="" class="rounded-circle mx-auto mb-3" height="100px" width="100px">
                @else
                <img src="{{ asset('themes/market/assets/images/seller-default-logo.png') }}" height="100px" width="100px" class="rounded-circle mx-auto mb-3" />
                @endif

                <a href="{{ route('marketplace.seller.show', $seller->url) }}" class=""><p class="font-weight-bold mb-0">{{$seller->shop_title}}</p></a>
                <p>@if(!empty($seller->city)) {{$seller->city}}, @endif{{$seller->state}}</p>
            </div>
            @endif
            @endforeach
        </div>
        <div class="text-center">
            <a href="/marketplace/start-selling" class="btn btn-lg btn-primary">Start selling on 2A Gun Show</a>
        </div>
    </div>
</div>

@push('scripts')

<script>
    $(document).ready(function() {
        $('.new-sellers-slider__xs').slick({
            centerMode: true,
            centerPadding: '0px',
            slidesToShow: 1,
            autoplay: true,
            arrows: true
        });
        $('.new-sellers-slider__md').slick({
            centerMode: true,
            centerPadding: '0px',
            slidesToShow: 3,
            autoplay: true,
            arrows: true
        });
        $('.new-sellers-slider__lg').slick({
            centerMode: true,
            centerPadding: '0px',
            slidesToShow: 5,
            autoplay: false,
            arrows: true
        });
    });
</script>

@endpush

@push('css')
<style>
    .new-sellers {
        background: rgba(235, 235, 235, 0.4);
    }
</style>
@endpush