<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Facades\DB;

class Review extends AbstractProduct
{
    /**
     * Returns the product's avg rating
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return float
     */
    public function getReviews($product)
    {
        static $reviews = [];

        $id = $product->product_id ?? $product->id;

        if (array_key_exists($id, $reviews)) {
            return $reviews[$id];
        }

        return $reviews[$id] = $product->reviews()->where('status', 'approved');
    }

    /**
     * Returns the product's avg rating
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return float
     */
    public function getAverageRating($product)
    {
        static $avgRating = [];

        $id = $product->product_id ?? $product->id;

        if (array_key_exists($id, $avgRating)) {
            return $avgRating[$id];
        }

        return $avgRating[$id] = number_format(round($product->reviews()->where('status', 'approved')->avg('rating'), 2), 1);
    }

    /**
     * Returns the total review of the product
     *
    * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return int
     */
    public function getTotalReviews($product)
    {
        static $totalReviews = [];

        $id = $product->product_id ?? $product->id;

        if (array_key_exists($id, $totalReviews)) {
            return $totalReviews[$id];
        }

        return $totalReviews[$id] = $product->reviews()->where('status', 'approved')->count();
    }

     /**
     * Returns the total rating of the product
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return int
     */
    public function getTotalRating($product)
    {
        static $totalRating = [];

        $id = $product->product_id ?? $product->id;

        if (array_key_exists($id, $totalRating)) {
            return $totalRating[$id];
        }

        return $totalRating[$id] = $product->reviews()->where('status','approved')->sum('rating');
    }

     /**
     * Returns the Percentage rating of the product
     *
    * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return int
     */
    public function getPercentageRating($product)
    {
        $reviews = $product->reviews()->where('status', 'approved')
                           ->select('rating', DB::raw('count(*) as total'))
                           ->groupBy('rating')
                           ->orderBy('rating','desc')
                           ->get();

        $totalReviews = $this->getTotalReviews($product);

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = round(($review->total / $totalReviews) * 100);

                        break;
                    } else {
                        $percentage[$i] = 0;
                    }
                }
            } else {
                $percentage[$i] = 0;
            }
        }

        return $percentage;
    }
}