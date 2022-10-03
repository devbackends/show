<?php

namespace Webkul\Authorize\Listeners;

use Webkul\Authorize\Models\AuthorizeCustomer;
use Webkul\Marketplace\Models\Seller;

class Authorize
{

    /**
     * @param Seller $seller
     * @return false
     */
    public function storeSellerCreds(Seller $seller): bool
    {

        if (request()->has('authorize')){
            $data = request()->get('authorize');

            $model = AuthorizeCustomer::query()->where('seller_id', $seller->id)->first();
            if (!$model) {
                $model = new AuthorizeCustomer();
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
        return AuthorizeCustomer::query()
            ->where('seller_id', '=', $seller->id)
            ->update(['is_approved' => $value]);
    }

}