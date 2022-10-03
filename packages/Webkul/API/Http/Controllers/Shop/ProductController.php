<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\API\Http\Resources\Catalog\Product as ProductResource;
use Validator;

class ProductController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection($this->productRepository->getAll(request()->input('category_id')));
    }

    /**
     * Returns a individual resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        return new ProductResource(
            $this->productRepository->findOrFail($id)
        );
    }

    /**
     * Returns product's additional information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function additionalInformation($id)
    {
        return response()->json([
            'data' => app('Webkul\Product\Helpers\View')->getAdditionalData($this->productRepository->findOrFail($id)),
        ]);
    }

    /**
     * Returns product's additional information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function configurableConfig($id)
    {
        return response()->json([
            'data' => app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($this->productRepository->findOrFail($id)),
        ]);
    }


    public function store(){

        if(!auth()->guard('api')->user()){
            return response()->json([
                'message' => 'Login is Required',
                'status' => 403
            ],403);
        }


        $validator = Validator::make(request()->all(), [
            'type'     => 'required',
            'attribute_family'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
           $data=request()->all();
           $sku= $data['sku'];
           $checkProductAlreadyExist=$this->productRepository->findWhere(['sku'=> $sku ])->first();
           if($checkProductAlreadyExist){
               return response()->json([
                   'message' => 'A product With the same Sku is already found',
                   'status' => 500
               ],200);
           }
            $attribute_family=app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->findWhere(['name'=> $data['attribute_family']])->first();
           if($attribute_family){
                $data['attribute_family_id']=$attribute_family->id;
                unset($data['attribute_family']);
            }else{
                return response()->json([
                    'status'  => 400 ,
                    'message' => 'Attribute Family is not found'
                ],400);
            }

            $data['customer_id']=auth()->guard('api')->user()->id;
            $format_create_request=$this->productRepository->formatCreateApiRequest($data);
            $product =  $this->productRepository->create($format_create_request);
            $product_info =$this->productRepository->find($product->id);
            return response()->json([
                'message' => 'Product created Successfuly',
                'product' =>  $product_info,
                'status' => 200
            ]);
        } catch (\Exception $exception){
            return response()->json([
                'status'  => 400 ,
                'message' => $exception->getMessage()
            ],400);
        }

    }

    public function update($id){

        if(!auth()->guard('api')->user()){
            return response()->json([
                'message' => 'Login is Required',
                'status' => 403
            ],403);
        }

        $validator = Validator::make(request()->all(), [
            'name'     => 'required',
            'short_description'     => 'required',
            'description' => 'required',
            'price' => 'required',
            'sku' => 'required',
            'url_key' => 'required',
            'condition' => 'required',
            'shipping_type' => 'required'
        ]);

        $vendor_id=app("Webkul\Marketplace\Repositories\SellerRepository")->getVendorBySellerId(auth()->guard('api')->user()->id);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }


        //check if shipping type is auto_calculated
        if(request()->all()['shipping_type'] == 'auto_calculated') {
            $validator = Validator::make(request()->all(), ['width' => 'required', 'height' => 'required', 'depth' => 'required', 'weight' => 'required', 'weight_lbs' => 'required']);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'status' => Response::HTTP_BAD_REQUEST,], Response::HTTP_BAD_REQUEST);
            }
        }

        //check if shipping type is flat_rate
        if(request()->all()['shipping_type'] == 'flate_rate') {
            $validator = Validator::make(request()->all(), ['shipping_price' => 'required']);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'status' => Response::HTTP_BAD_REQUEST,], Response::HTTP_BAD_REQUEST);
            }
        }


        try {
            $request_data = request()->all();
            $sku= $request_data['sku'];
            $url_key= $request_data['url_key'];

            $checkProductAlreadyExist=$this->productRepository->findWhere(['sku'=> $sku ])->first();
            if($checkProductAlreadyExist){
              if((int)$checkProductAlreadyExist->id!=(int)$id){
                  return response()->json([
                      'message' => 'A product With the same Sku is already found',
                      'status' => 500
                  ],200);
              }
            }
            $checkProductUrlKeyAlreadyExist=app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['url_key'=> $url_key ])->first();
            if($checkProductUrlKeyAlreadyExist){
                if((int)$checkProductUrlKeyAlreadyExist->product_id!=(int)$id){
                    return response()->json([
                        'message' => 'A product With the same url_key is already found',
                        'status' => 500
                    ],200);
                }
            }

            if($vendor_id){
                $request_data['vendor_id']=$vendor_id;
            }

            $formatted_request=$this->productRepository->getFormattedApiRequest($request_data);

            $product_info=$this->productRepository->update($formatted_request, $id);

            return response()->json([
                'message' => 'Product Updated Successfuly',
                'product' =>  $product_info,
                'status' => 200
            ],200);
        }catch (\Exception $exception){
            return response()->json([
                'status'  => 400 ,
                'message' => $exception->getMessage()
            ],400);
        }

    }
    public function addProductImage(){
        if(!auth()->guard('api')->user()){
            return response()->json([
                'message' => 'Login is Required',
                'status' => 403
            ],403);
        }


        $validator = Validator::make(request()->all(), [
            'product_id'     => 'required',
            'images'     => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
        $data=request()->all();
        $product_id=$data['product_id'];
        $product=$this->productRepository->find($product_id);
        if(!$product){
            return response()->json([
                'status'  => 500 ,
                'message' => "The provided product id is not exist"
            ],200);
        }
        if(isset($data['images'])){
            if(is_array($data['images'])){
                if(sizeof($data['images']) > 0){
                   return  app('Webkul\Product\Repositories\ProductImageRepository')->addProductImages($data);
                }
            }
        }

        return response()->json([
            'status'  => 500 ,
            'message'=> 'something went wrong'
        ],200);

        }catch (\Exception $exception){
            return response()->json([
                'status'  => 500 ,
                'message' => $exception->getMessage()
            ],200);
        }
    }

    public function removeProducImage($image_id){
       return   app('Webkul\Marketplace\Repositories\MpProductRepository')->removeImage($image_id);
    }
    public function getProductInventory($product_id){

        if(!auth()->guard('api')->user()){
            return response()->json([
                'message' => 'Login is Required',
                'status' => 403
            ],403);
        }
        $productFlat=app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=> $product_id ])->first();
        if($productFlat){
            $inventory=$productFlat->quantity -$productFlat->ordered_quantity;
            return response()->json([
                'inventory' => $inventory,
                'status' => 200
            ]);
        }

        return response()->json([
            'inventory' => 0,
            'status' => 200
        ]);
    }

    public function updateProductInventory($product_id){

        if(!auth()->guard('api')->user()){
            return response()->json([
                'message' => 'Login is Required',
                'status' => 403
            ],403);
        }

        $data=request()->all();
        $productFlat=app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=> $product_id ])->first();
        if($productFlat){
            $productFlat->quantity=$data['quantity'] + $productFlat->ordered_quantity;
            $productFlat->save();
            return response()->json([
                'inventory' => $data['quantity'],
                'status' => 200
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong , please contact us on info@devvly.com',
            'status' => 500
        ],200);
    }

    public function send(Request $request)
    {
     $data=request()->all();
     $sellerId=$data['sellerId'];
     $productId=$data['productId'];
     $inventory=$data['inventory'];
     $webhook= $this->productRepository->sendWebhook($inventory,$sellerId,$productId);
     return $webhook;
    }

    public function getProductBySku($sku){
        $product=$this->productRepository->findWhere(['sku'=> $sku ])->first();
        if($product){
            return response()->json([
                'message' => 'A product With the same Sku is already found',
                'status' => 200,
                'data' =>  $product
            ],200);
        }else{
            return response()->json([
                'message' => 'Product not found',
                'status' => 500,
            ],200);
        }
    }
}
