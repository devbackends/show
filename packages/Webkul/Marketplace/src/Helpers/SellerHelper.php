<?php

namespace Webkul\Marketplace\Helpers;

use Webkul\Marketplace\Models\FlatRateInfo;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\MarketplaceFedExShipping\Repositories\FedExRepository;
use Webkul\MarketplaceUpsShipping\Repositories\UpsRepository;
use Webkul\MarketplaceUspsShipping\Repositories\UspsRepository;

class SellerHelper
{

    public function setSellerShippingMethods($seller)
    {
        $shippingMethods = request('shipping_methods') ? implode(',', request('shipping_methods')) : '';
        if ($shippingMethods !== $seller->shipping_methods) {
            app(SellerRepository::class)
                ->update(['shipping_methods' => $shippingMethods], $seller->id);
        }
    }

    public function setFedexCredentials($seller): bool
    {
        $requiredKeys = ['account_id', 'password', 'meter_number', 'key'];
        $creds = request('fedex') ? request('fedex') : [];
        foreach ($requiredKeys as $key) {
            if (!isset($creds[$key]) || $creds[$key] == '') return false;
        }

        $repo = app(FedExRepository::class);
        $ifCredentialsExist = $repo->findOneWhere(['marketplace_seller_id' => $seller->id]);
        if (isset($ifCredentialsExist)) {
            $repo->update($creds, $ifCredentialsExist->id);
            session()->flash('success', trans('marketplace_fedex::app.shop.sellers.fedex.update-success'));
        } else {
            $creds['marketplace_seller_id'] = $seller->id;
            $repo->create($creds);
            session()->flash('success', trans('marketplace_fedex::app.shop.sellers.fedex.create-success'));
        }

        return true;

    }

    public function setUpsCredentials($seller): bool
    {
        $requiredKeys = ['account_id', 'password'];
        $creds = request('ups') ? request('ups') : [];
        foreach ($requiredKeys as $key) {
            if (!isset($creds[$key]) || $creds[$key] == '') return false;
        }

        $repo = app(UpsRepository::class);
        $ifCredentialsExist = $repo->findOneWhere(['ups_seller_id' => $seller->id]);
        if (isset($ifCredentialsExist)) {
            $repo->update($creds, $ifCredentialsExist->id);
            session()->flash('success', trans('marketplace_ups::app.shop.sellers.ups.update-success'));
        } else {
            $creds['ups_seller_id'] = $seller->id;
            $repo->create($creds);
            session()->flash('success', trans('marketplace_ups::app.shop.sellers.ups.create-success'));
        }

        return true;

    }

    public function setUspsCredentials($seller): bool
    {
        $requiredKeys = ['account_id', 'password'];
        $creds = request('usps') ? request('usps') : [];
        foreach ($requiredKeys as $key) {
            if (!isset($creds[$key]) || $creds[$key] == '') return false;
        }

        $repo = app(UspsRepository::class);
        $ifCredentialsExist = $repo->findOneWhere(['usps_seller_id' => $seller->id]);
        if (isset($ifCredentialsExist)) {
            $repo->update($creds, $ifCredentialsExist->id);
            session()->flash('success', trans('marketplace_usps::app.shop.sellers.usps.update-success'));
        } else {
            $creds['usps_seller_id'] = $seller->id;
            $repo->create($creds);
            session()->flash('success', trans('marketplace_usps::app.shop.sellers.usps.create-success'));
        }

        return true;

    }

    public function setFlatRateInfo($seller): bool
    {
        $requiredKeys = ['type', 'rate'];
        $data = request('flatrate') ? request('flatrate') : [];
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key]) || $data[$key] == '') return false;
        }

        $model = FlatRateInfo::where(['seller_id' => $seller->id])->first();

        // Need to create new one
        if (!$model) {
            $model = new FlatRateInfo();
            $model->seller_id = $seller->id;
        }

        $model->type = $data['type'];
        $model->rate = $data['rate'];
        return $model->save();
    }

    public function getSeller($order){
        $marketplace_order = app('Webkul\Marketplace\Repositories\OrderRepository')->findWhere(['order_id'=>$order->id]);

        $seller_id = $order->cart->seller_id;
        if ($marketplace_order) {
            $marketplace_order=$marketplace_order->first();
            if ($marketplace_order) {
                $seller_id = $marketplace_order->marketplace_seller_id;
            }
        }
        $marketplace_seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findWhere(['id'=>$seller_id])->first();
        return $marketplace_seller;
    }
    public function setSellerWebhooks($seller,$webhooks){
        app(SellerRepository::class)
            ->update(['webhooks' => $webhooks], $seller->id);
    }

}