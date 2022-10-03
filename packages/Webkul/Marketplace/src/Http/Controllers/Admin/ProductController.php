<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Marketplace\Mail\ProductApprovalNotification;
use Webkul\Marketplace\Mail\ProductDisapprovalNotification;


/**
 * Admin Product Controller
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
     * ProductFlatRepository object
     *
     * @var array
    */
    protected $productFlatRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductFlatRepository $productFlatRepository
     * @return void
     */
    public function __construct(ProductFlatRepository $productFlatRepository)
    {
        $this->_config = request('_config');

        $this->productFlatRepository = $productFlatRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Mixed
     */
    public function index()
    {

        return view($this->_config['view']);
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
            app('Webkul\Product\Repositories\ProductRepository')->delete($id);
            session()->flash('success', trans('marketplace::app.admin.response.delete-success', ['name' => 'Product']));
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
            $product = app('Webkul\Product\Repositories\ProductFlatRepository')->find($productId);


            if ($product) {
                app('Webkul\Product\Repositories\ProductFlatRepository')->delete($product->id);
            }
        }

        session()->flash('success', trans('marketplace::app.admin.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type']) || !$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $productIds = explode(',', $data['indexes']);

        foreach ($productIds as $productId) {
            $productFlat = $this->productFlatRepository->findWhere(['product_id'=>$productId])->first();

            if ($productFlat) {
                $product = $productFlat->product;

                $productFlat->update([
                        'is_seller_approved' => $data['update-options']
                    ]);

                if ($data['update-options']) {
                    try {
                        Mail::send(new ProductApprovalNotification($productFlat));
                    } catch (\Exception $e) {

                    }
                } else {
                    try {
                        Mail::send(new ProductDisapprovalNotification($productFlat));
                    } catch (\Exception $e) {

                    }
                }
            }
        }

        session()->flash('success', trans('marketplace::app.admin.products.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }
}