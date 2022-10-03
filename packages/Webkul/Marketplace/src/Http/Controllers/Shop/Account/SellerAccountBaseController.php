<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Repositories\MpProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class SellerAccountBaseController extends Controller
{
    /**
     * @var Seller
     */
    protected $seller;

    /**
     * SellerAccountBaseController constructor.
     * @param MpProductRepository $mpProductRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(MpProductRepository $mpProductRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        parent::__construct($mpProductRepository, $categoryRepository, $productRepository);

        $this->middleware('marketplace-seller');

        $customer = auth()->guard('customer')->user();
        if ($customer) {
            $this->seller = Seller::query()->firstWhere('customer_id', $customer->id);
        }
    }

}