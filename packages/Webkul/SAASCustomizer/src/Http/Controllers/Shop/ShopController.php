<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Marketplace\Models\Seller;
use Webkul\SAASCustomizer\Repositories\ProductRepository;
use Webkul\SAASCustomizer\Http\Controllers\Controller;


class ShopController extends Controller
{
    /**
     * ProductRepository Object
     */
    protected $productRepository;

    public function __construct(
        ProductRepository $productRepository
    )   {
        $this->productRepository = $productRepository;
    }

    public function getCategoryProducts($categoryId)
    {

        if(request()->get('cat')){
            $terms=['productCategories'=>request()->get('cat')];
        }else{
            $terms=['productCategories'=>$categoryId];
        }

        $params = request()->all();
        foreach ($params as $key =>$param){
            if($key!='cat'){
                $terms[$key]=$param;
            }
        }

        $sortOptions = [
            'sortField' => '',
            'sortOrder' => ''
        ];
        $productsArray=[];
        $paginationHtml='';
                $products = app('Webkul\Product\Repositories\ProductFlatRepository')->search($terms, $sortOptions);
                 /*$products = $this->productRepository->getAll($categoryId);*/
                if($products){
                    $productItems = $products->items();

                    /*$productsArray = $products->toArray();*/

                    if ($productItems) {
                        $formattedProducts = [];

                        $helper = new Marketplace();
                        foreach ($productItems as $product) {
                            array_push($formattedProducts, $helper->getFormattedProduct($product, $product->marketplace_seller_id ?? 0));
                        }

                        $productsArray['data'] = $formattedProducts;
                    }
                    $paginationHtml=$products->appends(request()->input())->links()->toHtml();
                }
                return response()->json($response ?? [
                        'products'       => $productsArray,
                        'paginationHTML' => $paginationHtml

                ]);



    }
}
