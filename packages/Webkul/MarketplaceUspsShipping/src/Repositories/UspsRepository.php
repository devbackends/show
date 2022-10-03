<?php

namespace Webkul\MarketplaceUspsShipping\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlatRepository;
use Webkul\Marketplace\Repositories\SellerRepository as SellerRepository;
use Illuminate\Container\Container as App;

/**
 * USPS Reposotory
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class UspsRepository extends Repository
{
    /**
     * SellerProduct Repository object
     *
     * @var array
     */
    protected $productFlatRepository;

    /**
     * SellerRepository object
     *
     * @var array
     */
    protected $sellerRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductFlatRepository $productFlatRepository
     * @return void
     */
    public function __construct(
        ProductFlatRepository $productFlatRepository,
        SellerRepository $sellerRepository,
        App $app
    )
    {
        $this->productFlatRepository = $productFlatRepository;

        $this->sellerRepository = $sellerRepository;

        parent::__construct($app);
    }
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\MarketplaceUspsShipping\Contracts\Usps';
    }

    /**
     * Get the sellerAdmin Product
     *
     * @return mixed
     */
    public function getSellerAdminData($cartItems, $code) {
        $sellerAdminProducts = [];

        foreach ($cartItems as $item) {
            $seller = null;
            if (isset($item->additional['seller_info']) && !$item->additional['seller_info']['is_owner']) {
                $seller = $this->sellerRepository->find($item->additional['seller_info']['seller_id']);
            } elseif ($seller && ! $seller->is_approved){
                continue;
            } elseif (! empty($this->productFlatRepository->getSellerByProductId($item->product_id)) ) {
                $seller = $this->productFlatRepository->getSellerByProductId($item->product_id);

            } else {
                array_push($sellerAdminProducts, $item);
            }

            if (isset($seller)) {
                $productFlat = $this->productFlatRepository->findOneWhere([
                    'product_id' => $item->product->id,
                    'marketplace_seller_id' => $seller->id,
                ]);

                $productFlat->quantity = $item->quantity;
                $productFlat->base_sku = $item->sku;
                $productFlat->type = $item->type;
                $productFlat->name = $item->name;
                $productFlat->coupon_code = $item->coupon_code;
                $productFlat->weight = $item->weight;
                $productFlat->base_weight = $item->weight;
                $productFlat->total_weight = $item->total_weight;
                $productFlat->base_total_weight = $item->base_total_weight;
                $productFlat->price = $item->price;
                $productFlat->base_total = $item->base_total;
                $productFlat->product_id = $item->product_id;
                $productFlat->cart_id = $item->cart_id;

                array_push($sellerAdminProducts ,$productFlat);
            }
        }

        return $sellerAdminProducts;
    }

    /**
     * Get the Allowde Services
     * @param $allServices
     * @return $secvices
     */
    public function validateAllowedMethods($service, $allowedServices)
    {

        $count = 0;
        $totalCount = count($allowedServices);

        // if ($totalCount > 0) {
            foreach ($allowedServices as $sellerMethods) {
                if ( in_array($service, $sellerMethods) ) {
                    $count += 1;
                }
            }
            if ( $count == $totalCount ) {
                return true;
            } else {
                return false;
            }
        // } else {
        //     return false;
        // }

    }

    /**
     * Get the Common Services for all the cartProduct
     * @param $allServices
     * @return $secvices
     */
    public function getAllowedMethods($allServices) {

        $allowedServices = explode(",", core()->getConfigData('sales.carriers.mpusps.services'));

        foreach ($allServices as $services) {
            $allowedMethod =[];
            foreach ($services as $service) {

                foreach ($service as $serviceType =>$fedexService) {
                    if (in_array($serviceType , $allowedServices)) {
                        $allowedMethod[] = [
                            $serviceType => $fedexService
                        ];
                    } else {
                        $notAllowed[] = [
                            $serviceType => $fedexService
                        ];
                    }
                }
            }

            if ($allowedMethod == null) {
                continue;
            } else {
                $allowedMethods[] = $allowedMethod;
            }

        }

        if (isset($allowedMethods)) {

            return $this->getCommonMethods($allowedMethods);
        } else {
            return false;
        }
    }


    /**
     * get the Common method
     *
     * @param $Methods
     * @return $finalServices
     */
    public function getCommonMethods($methods)
    {
        if (! $methods == null) {
            $countMethods = count($methods);

            foreach ($methods as $fedexMethods) {
                foreach ($fedexMethods as $key => $fedexMethod) {
                    $avilableServicesArray[] = $key;
                }
            }
        }

        if( isset($avilableServicesArray) ) {
            $countServices = array_count_values($avilableServicesArray);
            $finalServices = [];

            foreach ($countServices as $serviceType => $servicesCount) {

                foreach ($methods as $fedexMethods) {
                    foreach ($fedexMethods as $type => $fedexMethod) {
                        if ($serviceType == $type && $servicesCount == $countMethods) {
                            $finalServices[$serviceType][] =$fedexMethod;
                        }
                    }
                }

                if ($finalServices == null) {
                    continue;
                }
            }

            if (!empty($finalServices)) {
                return $finalServices;
            } else {
                return null;
            }
        }
    }
}

