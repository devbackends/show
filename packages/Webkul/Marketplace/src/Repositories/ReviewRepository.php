<?php

namespace Webkul\Marketplace\Repositories;

use DB;
use Webkul\Core\Eloquent\Repository;

/**
 * Seller Review Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Review';
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getRecentReviews($sellerId = null)
    {
        return $this->scopeQuery(function($query) use($sellerId) {
                return $query->distinct()->where('marketplace_seller_id', $sellerId)->where('status', 'approved')->orderBy('id', 'desc');
            })->paginate(5);
    }

    /**
     * Returns the seller's avg rating
     *
     * @param Seller $seller
     * @return float
     */
    public function getReviews($seller)
    {
        return $seller->reviews()->where('status', 'approved');
    }

    /**
     * Returns the seller's avg rating
     *
     * @param Seller $seller
     * @return float
     */
    public function getAverageRating($seller)
    {
        return number_format(round($seller->reviews()->where('status', 'approved')->average('rating'), 2), 1);
    }

    /**
     * Returns the total review of the seller
     *
    * @param Seller $seller
     * @return integer
     */
    public function getTotalReviews($seller)
    {
        return $seller->reviews()->where('status', 'approved')->count();
    }

     /**
     * Returns the total rating of the seller
     *
     * @param Seller $seller
     * @return integer
     */
    public function getTotalRating($seller)
    {
        return $seller->reviews()->where('status','approved')->sum('rating');
    }

     /**
     * Returns the Percentage rating of the seller
     *
    * @param Seller $seller
     * @return integer
     */
    public function getPercentageRating($seller)
    {
        $reviews = $seller->reviews()->where('status','approved')
                    ->select('rating', DB::raw('count(*) as total'))
                    ->groupBy('rating')
                    ->orderBy('rating','desc')
                    ->get();

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = round(($review->total / $this->getTotalReviews($seller)) * 100);
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