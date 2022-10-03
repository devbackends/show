<?php

namespace Webkul\Stripe\Listeners;

use Webkul\Stripe\Models\StripeCustomer;
use Webkul\Marketplace\Models\Seller;

class Stripe
{

    /**
     * @param Seller $seller
     * @return false
     */
    public function storeSellerCreds(Seller $seller): bool
    {

        if (request()->has('stripe')){
            $data = request()->get('stripe');

            $model = StripeCustomer::query()->where('seller_id', $seller->id)->first();
            if (!$model) {
                $model = new StripeCustomer();
                $model->seller_id = $seller->id;
            }

            $model->api_key = $data['api_key'];
            $model->public_key = $data['public_key'];
            $model->save();

        }

        return true;
    }

    /**
     * @param Seller $seller
     * @return bool
     */
    public function updateIsApproved(Seller $seller): bool
    {
        $value = request()->has('bank_approved') ? request()->get('bank_approved') : 0;
        return StripeCustomer::query()
            ->where('seller_id', '=', $seller->id)
            ->update(['is_approved' => $value]);
    }

}