<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Marketplace\Models\MarketplaceCustomerComprassion;
use Webkul\Marketplace\Repositories\MpProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Velocity\Helpers\Helper;

class ComprassionController extends Controller
{

    /**
     * @var MarketplaceCustomerComprassion
     */
    protected $model;

    /**
     * ComprassionController constructor.
     * @param MpProductRepository $mpProductRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @param MarketplaceCustomerComprassion $marketplaceCustomerComprassion
     */
    public function __construct(
        MpProductRepository $mpProductRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        MarketplaceCustomerComprassion $marketplaceCustomerComprassion
    ) {
        $this->model = $marketplaceCustomerComprassion;
        parent::__construct($mpProductRepository, $categoryRepository, $productRepository);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        if ($customer = auth()->guard('customer')->user()) {
            $compares = MarketplaceCustomerComprassion::where(['customer_id' => $customer->id])->get();
        } else {
            $compares = $request->get('data');
        }

        $products = [];
        foreach ($compares as $compare) {
            $product = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere([
                'product_id' => $compare['product_id']
            ])->first();

            if ($product) {
                $product = app(Marketplace::class)->getFormattedProduct($product, $compare['marketplace_seller_id']);

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
            $count = MarketplaceCustomerComprassion::where(['customer_id' => $customer->id])->count();
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

            $result = $this->model->where([
                'customer_id' => $customer->id,
                'product_id' => $productId,
                'marketplace_seller_id' => $marketplaceSellerId,
            ])->first();

            // Check if exist
            if ($result) {
                $response = [
                    'status'  => 'success',
                    'label'   => trans('velocity::app.shop.general.alert.success'),
                    'message' => trans('velocity::app.customer.compare.already_added'),
                ];
            } else {
                $model = new MarketplaceCustomerComprassion();
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
                'product_id' => $productId,
                'customer_id' => $customer->id,
                'marketplace_seller_id' => $marketplaceSellerId ?? 0,
            ];

            MarketplaceCustomerComprassion::where($wheres)->delete();
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