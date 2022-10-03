<?php

namespace Webkul\MarketplaceUspsShipping\Http\Controllers\Shop;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

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
}
