<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Repositories\MpProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object of velocity package
     *
     * @var MpProductRepository
     */
    protected $mpProductRepository;

    /**
     * CategoryRepository object of velocity package
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductRepository object of velocity package
     *
     * @var Helper;
     */
    protected $mpHelper;

    /**
     * Create a new controller instance.
     *
     * @param MpProductRepository $mpProductRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        MpProductRepository $mpProductRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    )
    {
        $this->_config = request('_config');

        $this->mpProductRepository = $mpProductRepository;

        $this->categoryRepository = $categoryRepository;

        $this->productRepository = $productRepository;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function isSeller()
    {
        $sellerRepository = app()->make('Webkul\Marketplace\Repositories\SellerRepository');

        $isSeller = $sellerRepository->isSeller(auth()->guard('customer')->user()->id);

        return  $isSeller ? true : false;
    }

    /**
     * @return string
     */
    private function getClientIp()
    {
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];

        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $option = explode(',', $_SERVER[$key]);
                $ip = trim(end($option));
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getClientLocationData()
    {
        try {
            $result = json_decode(file_get_contents('https://www.iplocate.io/api/lookup/' . $this->getClientIp()), 1);
            $result = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$result['latitude'].','.$result['longitude'].'&sensor=false&key=AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs'), 1);
            return $result['results'][0]['address_components'][2]['long_name'];
        } catch (\Exception $exception) {
            return null;
        }
    }
}
