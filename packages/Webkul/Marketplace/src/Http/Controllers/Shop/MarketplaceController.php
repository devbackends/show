<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Marketplace\Models\UserHelpRequest;
use Illuminate\Support\Facades\Validator;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Marketplace page controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MarketplaceController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @return View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    public function categoryDetails()
    {

        $slug = request()->get('category-slug');

        switch ($slug) {
            case 'new-products':
            case 'featured-products':
                $formattedProducts = [];
                $count = request()->get('count');

                $products = [];
                if ($slug == "new-products") {
                    $products = $this->mpProductRepository->getNewProducts($count);
                } else if ($slug == "featured-products") {
                    $products = $this->mpProductRepository->getFeaturedProducts($count);
                }

                $defaultSeller = app(SellerRepository::class)->find(0);

                foreach ($products as $product) {
                    if(isset($product['product_id'])){
                        $product_id=$product['product_id'];
                    }else{
                        $product_id=$product['id'];
                    }
                    $productFlat = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere([
                        'product_id' => $product_id
                    ])->first();

                    if ($productFlat) {
                        $product->qty = $product->product->totalQuantity();
                        $product->seller = app(SellerRepository::class)->find($productFlat->marketplace_seller_id);
                        $product->marketplace_seller_id = $productFlat->marketplace_seller_id;

                    } else {
                        $product->seller = $defaultSeller;
                    }
                    $formattedProduct = $this->formatProduct($product);
                    if ($productFlat) {
                        $formattedProduct['seller'] = app(SellerRepository::class)->find($productFlat->marketplace_seller_id);
                    } else {
                        $formattedProduct['seller'] = $defaultSeller;
                    }
                    $formattedProduct['product_id'] = $product_id;
                    array_push($formattedProducts, $formattedProduct);
                }

                $response = [
                    'status'   => true,
                    'products' => $formattedProducts,
                ];

                break;
            default:
                $categoryDetails = $this->categoryRepository->findByPath($slug);

                if ($categoryDetails) {
                    $list = false;
                    $customizedProducts = [];
                    $products = $this->productRepository->getAll($categoryDetails->id);

                    foreach ($products as $product) {
                        $productDetails = [];

                        $productDetails = array_merge($productDetails, $this->formatProduct($product));

                        array_push($customizedProducts, $productDetails);
                    }

                    $response = [
                        'status'           => true,
                        'list'             => $list,
                        'categoryDetails'  => $categoryDetails,
                        'categoryProducts' => $customizedProducts,
                    ];
                }

                break;
        }

        return $response ?? [
                'status' => false,
            ];
    }

    protected function formatProduct($product, $list = false, $metaInformation = [])
    {
        $editor = request()->get('forPageBuilder');
        $reviewHelper = app('Webkul\Product\Helpers\Review');
        $productImageHelper = app('Webkul\Product\Helpers\ProductImage');

        $totalReviews = $reviewHelper->getTotalReviews($product);

        $avgRatings = ceil($reviewHelper->getAverageRating($product));

        $galleryImages = $productImageHelper->getGalleryImages($product);
        $productImage = $productImageHelper->getProductBaseImage($product)['medium_image_url'];

        $largeProductImageName = "large-product-placeholder.png";
        $mediumProductImageName = "meduim-product-placeholder.png";

        if (strpos($productImage, $mediumProductImageName) > -1) {
            $productImageNameCollection = explode('/', $productImage);
            $productImageName = $productImageNameCollection[sizeof($productImageNameCollection) - 1];

            if ($productImageName == $mediumProductImageName) {
                $productImage = str_replace($mediumProductImageName, $largeProductImageName, $productImage);
            }
        }

        // To get correct quantity of marketlace seller main product
        request()->request->add(['product_id' => $product->product_id ?? $product->id]);

        if ($product->special_price) {
            if ($product->special_price_from && $product->special_price_to) {
                if (date('Y-m-d') >= $product->special_price_from && date('Y-m-d') <= $product->special_price_to) {
                    $price = $product->special_price;
                } else {
                    $price = $product->price;
                }
            } else {
                $price = $product->special_price;
            }
        } else {
            $price = $product->price;
        }
        //check if there is a free shipping
        $free_shipping_attribute=app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere(['code'=>'free_shipping'])->first();
        $is_free_shipping=0;
        if($free_shipping_attribute){
            $productAttributeValue = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->findWhere(['product_id'=>$product->product_id,'attribute_id'=> $free_shipping_attribute->id])->first();
            if($productAttributeValue){
                $is_free_shipping=$productAttributeValue->boolean_value;
            }
        }
        $addToCartVars = [
            'showCompare'       => true,
            'product'           => $product,
            'addWishlistClass'  => ! (isset($list) && $list) ? '' : '',
            'btnText'           => (isset($metaInformation['btnText']) && $metaInformation['btnText'])
                ? $metaInformation['btnText'] : null,
            'moveToCart'        => (isset($metaInformation['moveToCart']) && $metaInformation['moveToCart'])
                ? $metaInformation['moveToCart'] : null,
            'addToCartBtnClass' => ! (isset($list) && $list) ? 'small-padding' : '',
        ];

        $response = [
            'isFreeShipping' => $is_free_shipping,
            'isInStock'       => $product->getTypeInstance()->haveSufficientQuantity(1,$product->marketplace_seller_id),
            'url'               => $product->url_key,
            'type'              => $product->type,
            'avgRating'         => $avgRatings,
            'totalReviews'      => $totalReviews,
            'image'             => $productImage,
            'galleryImages'     => $galleryImages,
            'name'              => $product->name,
            'slug'              => $product->url_key,
            'new'              => $product->new,
            'description'       => $product->description,
            'shortDescription'  => $product->short_description,
            'firstReviewText'   => trans('velocity::app.products.be-first-review'),
            'priceHTML'         =>  (isset($product->marketplace_seller_id)) ? $product->getTypeInstance()->getPriceHtml($product->marketplace_seller_id) : $product->getTypeInstance()->getPriceHtml(),
            'isWithinThirtyDays' => strtotime($product->created_at) < strtotime('-30 days') ? 0 : 1
        ];
        if($editor){
            $response['addToCartVars'] = $addToCartVars;
        }
        else {
            $response['addToCartHtml'] = view('shop::products.add-to-cart', $addToCartVars)->render();
        }
        return $response;
    }

    public function error(){
     return   abort(500);
    }
    public function startSelling(){
        return view($this->_config['view']);
    }

    public function communityIndex()
    {
        return view('marketplace::community');
    }

    public function userHelpForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message.name' => 'required',
            'message.email' => 'required|email',
            'message.text' => 'required',
        ], [
            'email' => 'Please, provide valid email'
        ]);
        $message = $validator->validate()['message'];

        $model = new UserHelpRequest();
        if ($model->create($message)) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }

    }
}