@push('css')
    <style>
        .product-detail .seller-info {
            margin-bottom: 15px;
        }

        .seller-info .star-blue-icon {
            vertical-align: text-top;
        }
    </style>
@endpush

<?php 

    $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository');

    $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository');

    $seller = $productFlatRepository->getSellerByProductId($product->product_id);

?>

@if ($seller && $seller->is_approved)

    <?php $sellerProduct = $productFlatRepository->getMarketplaceProductByProduct($product->product_id, $seller->id); ?>

    @if ($sellerProduct->is_approved)

        <div class="seller-info">

            {!!
                __('marketplace::app.shop.products.sold-by', [
                        'url' => "<a href=" . route('marketplace.seller.show', $seller->url) . ">" . $seller->shop_title . " [<i class='icon star-blue-icon'></i>" . $reviewRepository->getAverageRating($seller) . "]</a>"
                    ]) 
            !!}

        </div>

    @endif

@endif