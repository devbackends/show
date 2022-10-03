<?php

namespace Devvly\FluidPayment\Listeners;

use Devvly\FluidPayment\Models\FluidCustomer;
use Webkul\Marketplace\Models\Seller;

class Fluid
{

    /**
     * @param Seller $seller
     * @return false
     */
    public function storeSellerCreds(Seller $seller): bool
    {
   /*     if (!request()->has('fluid') && !request()->has('seller-fluid')) return false;*/
        if(request()->has('fluid')){
            $data = request()->get('fluid');
            $model = FluidCustomer::query()->where('seller_id', $seller->id)->where('type','2acommerce-gateway')->first();
            if (!$model) {
                $model = new FluidCustomer();
                $model->seller_id = $seller->id;
            }

            $model->api_key = $data['api_key'];
            $model->public_key = $data['public_key'];
            $model->is_approved =1;
            $model->type = '2acommerce-gateway';
            $model->save();
        }
        if(request()->has('seller-fluid')){
            $data = request()->get('seller-fluid');
            $model = FluidCustomer::query()->where('seller_id', $seller->id)->where('type','seller-gateway')->first();
            if (!$model) {
                $model = new FluidCustomer();
                $model->seller_id = $seller->id;
            }
            $model->api_key = $data['api_key'];
            $model->public_key = $data['public_key'];
            $model->is_approved =1;
            $model->type = 'seller-gateway';
            $model->save();
        }
        if(request()->has('bluedog')){
            $data = request()->get('bluedog');
            $model = FluidCustomer::query()->where('seller_id', $seller->id)->where('type', 'bluedog-gateway')->first();
            if (!$model) {
                $model = new FluidCustomer();
                $model->seller_id = $seller->id;
            }
            $model->api_key = $data['api_key'];
            $model->public_key = $data['public_key'];
            $model->is_approved =1;
            $model->type = 'bluedog-gateway';
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
        return FluidCustomer::query()
            ->where('seller_id', '=', $seller->id)
            ->update(['is_approved' => $value]);
    }

}