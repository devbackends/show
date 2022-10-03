<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use http\Env\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Models\Seller as SellerModel;
use Webkul\Marketplace\Service\SellerType;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Marketplace\Repositories\MpProductRepository as Product;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Marketplace\Repositories\SellerRepository as Seller;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * CategoryRepository object
     *
     * @var array
     */
    protected $category;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;


    /**
     * SellerRepository object
     *
     * @var array
     */
    protected $seller;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamily
     * @param  Webkul\Category\Repositories\CategoryRepository         $category
     * @param  Webkul\Marketplace\Repositories\SellerRepository        $seller
     * @return void
     */
    public function __construct(
        Product $product,
        AttributeFamily $attributeFamily,
        Category $category,
        Seller $seller
    )
    {
        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->product = $product;


        $this->seller = $seller;

        $this->_config = request('_config');

        $this->middleware('marketplace-seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isSeller = $this->seller->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $families = $this->attributeFamily->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store()
    {

        if(request()->input('sku') == ''){
            $seller = $this->seller->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            $sku=$seller->id .'-'.substr(request()->input('type'), 0, 2).'-'.date("His");
            request()->merge(['sku' => $sku]);
        }


        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());


        if (\Request::getRequestUri() == '/api/add-product') {
            return response()->json([
                'message' => 'Your Product has been created successfully.',
                'product' => $product
            ]);
        }


        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $product = $this->product->with(['variants'])->findorFail($id);
        $productFlat = app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$product->id])->first();


        $categories = $this->category->getCategoryTree();

        // $categories = $this->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        //get configurable families
        $configurableFamily = $this->attributeFamily->find($product->attribute_family_id);
        $configurableAttributes = [];
        foreach ($configurableFamily->configurable_attributes as $key => $attribute){
            $configurableAttributes[$key] = $attribute->toArray();
            $result=DB::select(' select `selected_attribute_options` from `product_super_attributes` where `product_id` = '.$id.' and `attribute_id` ='.$attribute->id);
            $selected_attribute_options='[]';
            if(isset($result[0]->selected_attribute_options)){
                $selected_attribute_options=$result[0]->selected_attribute_options;
            }
            $configurableAttributes[$key]['selected_attribute_options']=json_decode($selected_attribute_options);
            foreach ($attribute->options as $option) {
                    $configurableAttributes[$key]['options'][] = [
                        'id'           => $option->id,
                        'admin_name'   => $option->admin_name,
                        'sort_order'   => $option->sort_order,
                        'swatch_value' => $option->swatch_value,
                    ];
                }
        }

        $dynamicGroup='';
        foreach ($product->attribute_family->attribute_groups as $attributeGroup){
            if($attributeGroup->name=='Attributes'){
                foreach ($attributeGroup->custom_attributes as $key => $attribute){
                   $attribute->text=$attribute->admin_name;
                    $attribute->options=$attribute->options;
                    foreach ($attribute->options as $option) {
                        $option->text=$option->admin_name;
                    }
                }
                $dynamicGroup=$attributeGroup;
            }
        }
        $product->name=$productFlat->name;
        $product->description=$productFlat->description;
        $product->short_description=$productFlat->short_description;
        return view($this->_config['view'], compact('product', 'categories','configurableAttributes','dynamicGroup'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Webkul\Product\Http\Requests\ProductForm $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(ProductForm $request, $id)
    {
        $request_data = request()->all();
        (isset($request_data['auto_generate_sku'])) ?  $this->updateAutoGenerateSku($request_data['auto_generate_sku']) :  $this->updateAutoGenerateSku(0);
        $product = \Webkul\Product\Models\Product::query()->find($id);
        if(isset($request_data['formated_categories'])){
            $request_data['categories']=explode(',',$request_data['formated_categories']);
            $request_data['categories']= array_filter($request_data['categories'], function($value){
               if(!is_null($value) && $value !== ''){
                   return $value;
               }
            });
            unset($request_data['formated_categories']);
        }

        if($product->type=='configurable'){
            $variants_array= $this->product->validateVariants($request_data,$id);
            $request_data['variants']=$variants_array;
            unset($request_data['formatted_variants']);
        }

        if(!isset($request_data['featured'])) {
            $request_data['featured'] = 0;
        }
        if (!isset($request_data['new'])) {
            $request_data['new'] = 0;
        }
        $request_data['url_key'] = $this->cleanUrl($request_data['url_key']);



        // Condition validation
        if (isset($request['condition']) && !empty($request['condition'])) {
          $conditionOption = AttributeOption::query()->find($request_data['condition']);
          if (strtolower($conditionOption->admin_name) === 'used') {
              if(isset($request_data['images'])){
                  $key = array_key_first($request_data['images']);
                  if ($request_data['images'][$key] === '' && $product->images()->get()->count() ==0) {
                      session()->flash('error', 'If your product is in used condition, you should upload at least one image.');
                      return redirect()->back();
                  }
              }
          }
        }


        //check if product family id is ammunition , if yes check that there is shipping methods allowd other than usps
        $seller = $this->seller->findOneByField('customer_id', auth()->guard('customer')->user()->id);
        $sellerShippingMethods=explode(',',$seller->shipping_methods);
        $ammo_attribute = app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->findwhere(["code" => 'Ammunition'])->first();
        if($ammo_attribute){
            if($product->attribute_family_id==$ammo_attribute->id){

                if(in_array('mpusps',$sellerShippingMethods) && sizeof($sellerShippingMethods) == 1 && $request_data['shipping_price']==0) {
                    session()->flash('error', 'USPS does not allow ammo shipments, please either enabled an additional shipping provide in your setting or set a flat rate shipping fee for this product.');
                    return redirect()->back();
                }

            }
        }
        if (isset($request['booking']) && !empty($request['booking'])) {

            if ($product->type === 'booking') {
                $request_data['locale'] = 'en';
                $product->getTypeInstance()->update($request_data, $id, 'id');
            }
        }

        $updated_product=$this->product->update($request_data, $id);
        $flasfMessage = 'Product updated successfully.';
        if (isset($request_data['status']) && (int)$request_data['status']) {
            if (!$updated_product->is_listing_fee_charged) {
                $seller = SellerModel::query()->find($updated_product->marketplace_seller_id);
                if ($seller && $seller instanceof SellerModel) {
                    (new SellerType($seller))->listingFee(false, $updated_product);
                    if($seller->type=='basic'){
                        $flasfMessage = ' Your product has been published. You will be charged a one-time listing fee of $0.99. If in the future you decide to edit this product, you will not be charged for republishing.';
                    }
                }
            }
        }

        session()->flash('success', $flasfMessage);

        return redirect()->route($this->_config['redirect']);
    }
    public function updateAutoGenerateSku($auto_generate_sku){
        $seller=$this->seller->findWhere(['customer_id'=> auth()->guard('customer')->user()->id])->first();
        $seller->auto_generate_sku=$auto_generate_sku;
        $seller->save();
    }
    /**
     * get visible category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getVisibleCategoryTree($id)
    {
        return $this->category->getModel()->orderBy('position', 'ASC')->where('status', 1)->descendantsOf($id)->toTree();
    }
    function validateFormattedImages($data){
        $images=[];
        foreach ($data as $key=> $image){
            $images['image_'.$key]=$image;
        }
        return $images;
    }
    function cleanUrl($string) {
        $string=strtolower($string);

        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allow_delete=true;
        //check if the product is ordered from a customer before delete , id yes don't allow to delete
        $orders_items=app('Webkul\Marketplace\Repositories\OrderItemRepository')->getOrdersItemsByProduct($id);
        if($orders_items){
            foreach ($orders_items as $orders_item){
                $order=app('Webkul\Marketplace\Repositories\OrderRepository')->getOrderByOrderItem($orders_item->marketplace_order_id);
                if($order){
                    if($order->status=='processing'){$allow_delete=false;}
                }
            }
        }
        if($allow_delete){
            $this->product->delete($id);
            session()->flash('success', 'Product deleted successfully.');
            return redirect()->back();
        }else{
            session()->flash('error', 'Product cannot be deleted because it is found in an in-process order');
            return redirect()->back();
        }
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $product = app('Webkul\Product\Repositories\ProductFlatRepository')->getMarketplaceProductByProduct($productId);

            if ($product) {
                    $this->product->delete($product->product_id);
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    public function addSuperAttributes($product_id){
        $data=request()->all();

        $product=$this->product->find($product_id);
        foreach ($data['super_attributes'] as $attr){
            if(isset($attr['code'])){
                $array[$attr['code']]=$attr['selected_attribute_options'];
            }
        }
        if($array){
            $add_super_attributes=$this->product->addSuperAttributes( array('super_attributes'=>$array) ,$product,$data['auto_generate_variation']);
        }
    }
    public function generateVariants($product_id){
        $data=request()->all();
        $product=$this->product->find($product_id);
        foreach ($data['super_attributes'] as $attr){
            if(isset($attr['code'])){
                $array[$attr['code']]=$attr['selected_attribute_options'];
            }
        }
        if($array){
            $generateVariants=$this->product->generateVariants( array('super_attributes'=>$array), $product);
        }

    }
    public function getVariants($product_id){
        $product=$this->product->find($product_id);
        $variants=[];
        foreach ($product->variants as $key => $variant){
            $productFlat= app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$variant->id])->first();
            if($productFlat){
                $variant->quantity=$productFlat->quantity;
            }else{
                $variant->quantity=0;
            }
            $variant->images=$variant->images->toArray();
            $var=$variant->toArray();
            array_push($variants,$var);
        }
       return $variants;
    }

    public function removeProductImage(){
        $data=request()->all();
        $image_id=$data['id'];
      return  $this->product->removeImage($image_id);
    }
    public function addProductImage(){
        $data=request()->all();
        $nb_of_images=$data['nb_of_images'];

        $data['images']=[];

        for($i=0;$i<$nb_of_images;$i++){
            if(isset($data['image_'.$i])){
                array_push($data['images'],$data['image_'.$i]);
            }
        }

        $images=$this->product->uploadProductImages($data);
        return array('status'=>'success','images'=>$images);
    }

    public function sortImagesOrder(){
        $data=request()->all();
        return $this->product->sortImagesOrder($data);
    }

  public function validateProductUrl(){
     $data=request()->all();
      $product=app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['url_key'=>$data['url_key']])->first();
      if(isset($product->product_id)){
          if($product->product_id!=$data['product_id']){
              return array('status'=>'failed','message'=>'There is already a product with this url');
          }
      }
      return array('status'=>'success');
  }
    public function suggestBySeller(){
        $data=request()->all();
        try{
            $headers = "From: ".auth()->guard('customer')->user()->email . "\r\n" ;
            if(mail("support@2agunshow.com","Suggestion to add new ".$data['type'].' from '.auth()->guard('customer')->user()->first_name.' '.auth()->guard('customer')->user()->last_name ,$data['suggestion'],$headers)){
                return array('status'=>'success');
            }else{
                return array('status'=>'failed');
            }
        } catch(Exception $exception) {
            return array('status'=>'failed');
        }
        return array('status'=>'success');
    }
}