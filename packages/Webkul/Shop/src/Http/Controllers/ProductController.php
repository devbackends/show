<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;

class ProductController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * ProductAttributeValueRepository object
     *
     * @var \Webkul\Product\Repositories\ProductAttributeValueRepository
     */
    protected $productAttributeValueRepository;

    /**
     * ProductDownloadableSampleRepository object
     *
     * @var \Webkul\Product\Repositories\ProductDownloadableSampleRepository
     */
    protected $productDownloadableSampleRepository;

    /**
     * ProductDownloadableLinkRepository object
     *
     * @var \Webkul\Product\Repositories\ProductDownloadableLinkRepository
     */
    protected $productDownloadableLinkRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\productAttributeValueRepository  $productAttributeValueRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableSampleRepository  $productDownloadableSampleRepository
     * @param  \Webkul\Product\Repositories\ProductDownloadableLinkRepository  $productDownloadableLinkRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductAttributeValueRepository $productAttributeValueRepository,
        ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        ProductDownloadableLinkRepository $productDownloadableLinkRepository
    )
    {
        $this->productRepository = $productRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->productDownloadableSampleRepository = $productDownloadableSampleRepository;

        $this->productDownloadableLinkRepository = $productDownloadableLinkRepository;

        parent::__construct();
    }

    public function buyall() {
        return view($this->_config['view']);
    }

    public function getAllProducts($paginate, $categoryId) {

        $products = $this->productRepository->getAllWithPagination($categoryId, $paginate);

        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = $this->formatProduct($product);
        }

        return response()->json(['products' => $formattedProducts]);
    }

    /**
     * Download image or file
     *
     * @param  int  $productId
     * @param  int  $attributeId
     * @return \Illuminate\Http\Response
     */
    public function download($productId, $attributeId)
    {
        $productAttribute = $this->productAttributeValueRepository->findOneWhere([
            'product_id'   => $productId,
            'attribute_id' => $attributeId,
        ]);

        return Storage::download($productAttribute['text_value']);
    }

    /**
     * Download the for the specified resource.
     *
     * @return \Illuminate\Http\Response|\Exception
     */
    public function downloadSample()
    {
        try {
            if (request('type') == 'link') {
                $productDownloadableLink = $this->productDownloadableLinkRepository->findOrFail(request('id'));

                if ($productDownloadableLink->sample_type == 'file') {
                    return Storage::download($productDownloadableLink->sample_file);
                } else {
                    $fileName = $name = substr($productDownloadableLink->sample_url, strrpos($productDownloadableLink->sample_url, '/') + 1);

                    $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                    copy($productDownloadableLink->sample_url, $tempImage);

                    return response()->download($tempImage, $fileName);
                }
            } else {
                $productDownloadableSample = $this->productDownloadableSampleRepository->findOrFail(request('id'));

                if ($productDownloadableSample->type == 'file') {
                    return Storage::download($productDownloadableSample->file);
                } else {
                    $fileName = $name = substr($productDownloadableSample->url, strrpos($productDownloadableSample->url, '/') + 1);

                    $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                    copy($productDownloadableSample->url, $tempImage);

                    return response()->download($tempImage, $fileName);
                }
            }
        } catch(\Exception $e) {
            abort(404);
        }
    }

    protected function formatProduct($product)
    {
        $productImageHelper = app('Webkul\Product\Helpers\ProductImage');

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
            'addWishlistClass'  => '',
            'btnText'           => null,
            'moveToCart'        => null,
            'addToCartBtnClass' => '',
        ];
        $response = [
            'isFreeShipping' => $is_free_shipping,
            'isInStock'       => $product->getTypeInstance()->haveSufficientQuantity(1,$product->marketplace_seller_id),
            'url'               => $product->url_key,
            'type'              => $product->type,
            'image'             => $productImage,
            'galleryImages'     => $galleryImages,
            'id'                => $product->id,
            'name'              => $product->name,
            'slug'              => $product->url_key,
            'new'               => $product->new,
            'description'       => $product->description,
            'shortDescription'  => $product->short_description,
            'categoryId'        => $product->category_id,
            'firstReviewText'   => trans('velocity::app.products.be-first-review'),
            'priceHTML'         => (isset($product->marketplace_seller_id)) ? $product->getTypeInstance()->getPriceHtml($product->marketplace_seller_id) : $product->getTypeInstance()->getPriceHtml(),
            'seller'            => app(SellerRepository::class)->find($product->marketplace_seller_id),
            'isWithinThirtyDays' => strtotime($product->created_at) < strtotime('-30 days') ? 0 : 1
        ];
        $forBlock = request()->get('forBlock');
        if ($forBlock) {
            $response['addToCartVars'] = $addToCartVars;
        }
        else {
            $response['addToCartHtml'] = view('shop::products.add-to-cart', $addToCartVars)->render();
        }
        return $response;
    }

    public function findBySku($sku){
        if ($product = $this->productRepository->findOneByField('sku', $sku)) {
            return $this->formatProduct($product);
        }
        abort(404);
    }
}
