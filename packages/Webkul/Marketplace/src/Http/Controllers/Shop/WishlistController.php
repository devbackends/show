<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Marketplace\Models\MarketplaceCustomerWishlist;
use Webkul\Marketplace\Repositories\MpProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;

class WishlistController extends Controller
{

    /**
     * @var MarketplaceCustomerWishlist
     */
    protected $model;

    /**
     * ComprassionController constructor.
     * @param MpProductRepository $mpProductRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @param MarketplaceCustomerWishlist $marketplaceCustomerWishlist
     */
    public function __construct(
        MpProductRepository $mpProductRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        MarketplaceCustomerWishlist $marketplaceCustomerWishlist
    ) {
        $this->model = $marketplaceCustomerWishlist;
        parent::__construct($mpProductRepository, $categoryRepository, $productRepository);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        if ($customer = auth()->guard('customer')->user()) {
            $list = MarketplaceCustomerWishlist::where(['customer_id' => $customer->id])->get();
        } else {
            $list = $request->get('data');
        }

        $products = [];
        foreach ($list as $item) {
            $product = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere([
                'product_id' => $item['product_id']
            ])->first();

            if ($product) {
                $product = app(Marketplace::class)->getFormattedProduct($product, $item['marketplace_seller_id']);

                $products[] = $product;
            }

        }

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getCount(): JsonResponse
    {
        $count = 0;
        if ($customer = auth()->guard('customer')->user()) {
            $count = MarketplaceCustomerWishlist::where(['customer_id' => $customer->id])->count();
        }

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addProduct(Request $request): JsonResponse
    {
        $productId = $request->get('productId');
        $marketplaceSellerId = $request->get('marketplaceSellerId');
        if (!$marketplaceSellerId) $marketplaceSellerId = 0;

        $response = [];
        $customer = auth()->guard('customer')->user();
        if ($customer && $productId) {

            $wheres = [
                'product_id' => $productId,
                'customer_id' => $customer->id,
                'marketplace_seller_id' => $marketplaceSellerId ?? 0,
            ];

            $result = $this->model->where($wheres)->get()->first();

            // Check if exist
            if ($result) {
                $response = [
                    'status'  => 'success',
                    'label'   => trans('velocity::app.shop.general.alert.success'),
                    'message' => trans('velocity::app.customer.wishlist.already_added'),
                ];
            } else {
                $model = new MarketplaceCustomerWishlist();
                $model->customer_id = $customer->id;
                $model->product_id = $productId;
                $model->marketplace_seller_id = $marketplaceSellerId;

                if ($model->save()) {
                    $response = [
                        'status'  => 'success',
                        'label'   => trans('velocity::app.shop.general.alert.success'),
                        'message' => trans('velocity::app.shop.general.alert.success'),
                    ];
                }
            }
        }

        if (empty($response)) {
            $response = [
                'status'  => 'error',
                'label'   => 'Error',
                'message' => 'Something went wrong!',
            ];
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteProduct(Request $request): JsonResponse
    {
        $productId = $request->get('productId');
        $marketplaceSellerId = $request->get('marketplaceSellerId');

        $response = [];
        if ($productId && $customer = auth()->guard('customer')->user()) {

            $wheres = [
                'customer_id' => $customer->id,
            ];
            if ($productId !== 'all') {
                $wheres['product_id'] = $productId;
                $wheres['marketplace_seller_id'] = $marketplaceSellerId ?? 0;
            }

            MarketplaceCustomerWishlist::where($wheres)->delete();
            $response = [
                'status' => 'success',
                'label' => 'Success',
                'message' => 'Item succesfully removed',
            ];
        }

        if (empty($response)) {
            $response = [
                'status' => 'error',
                'lavel' => 'Error',
                'message' => 'Something went wrong',
            ];
        }

        return response()->json($response);

    }

}