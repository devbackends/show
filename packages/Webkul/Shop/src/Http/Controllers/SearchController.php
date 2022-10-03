<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\View\View;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

 class SearchController extends Controller
{

    /**
     * Index to handle the view loaded with the search results
     *
     * @return View
     */
    public function index(): View
    {
        return view($this->_config['view']);

    }
    public function search($type,$search){

        $terms=[];
       if($search){
            $terms['search']=$search;
        }
        $params = request()->all();

        foreach ($params as $key =>$param){
            if($key!='type'){
                $terms[$key]=$param;
            }
        }

        $sortOptions = [
            'sortField' => request()->get('sort'),
            'sortOrder' => request()->get('order')
        ];

        $pagination = [];
        $data = [];

        switch ($type) {
            case 'product':
                $helper = new Marketplace();
                    $pagination = app(ProductFlatRepository::class)->search($terms, $sortOptions);
                    if ($pagination) {
                        $data = array_map(function ($product) use ($helper) {
                            return $helper->getFormattedProduct($product, $product->marketplace_seller_id)->toArray(true);
                        }, $pagination->items());
                    }

                break;
            case 'category':
                $pagination = app(CategoryRepository::class)->search($terms, $sortOptions);
                if ($pagination) {
                    $data = $pagination->items();
                }
                break;
            case 'seller':
                $pagination = app(SellerRepository::class)->search($terms, $sortOptions);
                if ($pagination) {
                    $data = $pagination->items();
                }
                break;
        }
        $paginationHtml=[];
        if($pagination){
            $paginationHtml=$pagination->appends(request()->input())->links()->toHtml();
        }
        $array=[];
        foreach ($data as $item){
            array_push($array,$item);
        }
        return response()->json($response ?? [
                'data'       => $array,
                'paginationHTML' => $paginationHtml

            ]);
    }
}
