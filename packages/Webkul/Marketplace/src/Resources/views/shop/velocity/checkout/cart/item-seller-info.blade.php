<?php

    $sellerRepository = app('Webkul\Marketplace\Repositories\SellerRepository');

    $productFlatRepository = app('Webkul\Product\Repositories\ProductFlatRepository');

    $reviewRepository = app('Webkul\Marketplace\Repositories\ReviewRepository');

    if (isset($item->additional['seller_info']) && !$item->additional['seller_info']['is_owner']) {
        $seller = $sellerRepository->find($item->additional['seller_info']['seller_id']);
    } else {
        $seller = $productFlatRepository->getSellerByProductId($item->product_id);
    }

?>

@if ($seller && $seller->is_approved)

    <?php $sellerProduct = $productFlatRepository->getMarketplaceProductByProduct($item->product->id, $seller->id); ?>

    @if (isset($sellerProduct) && $sellerProduct->is_approved)

        <div class="seller-info" style="margin-bottom: 10px;">

            {!!
                __('marketplace::app.shop.products.sold-by', [
                        'url' => "<a href=" . route('marketplace.seller.show', $seller->url) . ">" . $seller->shop_title . " [<i class='icon star-blue-icon' style='vertical-align: text-top'></i>" . $reviewRepository->getAverageRating($seller) . "]</a>"
                    ])
            !!}

        </div>

    @endif

@endif