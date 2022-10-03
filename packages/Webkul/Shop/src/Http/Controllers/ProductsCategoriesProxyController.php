<?php

namespace Webkul\Shop\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Models\Product;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Helpers\ProductSellers;
use Webkul\Product\Repositories\ProductRepository;

class ProductsCategoriesProxyController extends Controller
{
    /**
     * CategoryRepository object
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductSellers
     */
    protected $productSellerHelper;

    /**
     * @var SellerRepository
     */
    protected $sellerRepository;

    /**
     * Create a new controller instance.
     *
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @param ProductSellers $productSellerHelper
     * @param SellerRepository $sellerRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ProductSellers $productSellerHelper,
        SellerRepository $sellerRepository
    )
    {
        $this->categoryRepository = $categoryRepository;

        $this->productRepository = $productRepository;

        $this->productSellerHelper = $productSellerHelper;

        $this->sellerRepository = $sellerRepository;

        parent::__construct();
    }

    /**
     * Show product or category view. If neither category nor product matches, abort with code 404.
     *
     * @param Request $request
     * @return View|Exception
     */
    public function index(Request $request)
    {
        $slugOrPath = trim($request->getPathInfo(), '/');
        $customer_is_seller = false;
        if (auth()->guard('customer')->user()) {
            $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            if ($seller and $seller->is_approved == 1) {
                $customer_is_seller = true;
            }
        }
        if (preg_match('/^([a-z0-9-]+\/?)+$/', $slugOrPath)) {

            if ($category = $this->categoryRepository->findByPath($slugOrPath)) {

                return view($this->_config['category_view'], compact('category'));
            }

            if ($product = $this->productRepository->findBySlug($slugOrPath)) {

                $customer = auth()->guard('customer')->user();

                $products = $this->productSellerHelper->getSellersProductsByProduct($product);

                return view($this->_config['product_view'], compact('product', 'products', 'customer','customer_is_seller'));
            }

        }

        abort(404);
    }

    /**
     * @param $slug
     * @return Application|Factory|View
     */
    public function product($slug)
    {
        $customer_is_seller = false;
        if (auth()->guard('customer')->user()) {
            $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            if ($seller and $seller->is_approved == 1) {
                $customer_is_seller = true;
            }
        }
        if (preg_match('/^([a-z0-9-]+\/?)+$/', $slug)) {

            if ($product = $this->productRepository->findBySlug($slug)) {

                $customer = auth()->guard('customer')->user();

                /*start booking product logic*/
                $bookingProduct='';
                $bookingProductDefaultSlot='';
                $bookingProductRental ='';
                $event_tickets='';
                $product->child=$product->variants()->get();
                foreach ($product->child as $key => $child){
                    $product->child[$key]->quantity=$product->child[$key]->totalQuantity();
                }
                if($product->type == 'booking'){
                    $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->product_id);
                    if($bookingProduct){
                        $bookingProductDefaultSlot=$bookingProduct->default_slot;
                        if($bookingProductDefaultSlot){
                            if(is_string($bookingProductDefaultSlot->slots)){
                                $bookingProductDefaultSlot->slots=json_decode($bookingProductDefaultSlot->slots);
                            }
                            if(is_string($bookingProductDefaultSlot->repetition_sequence)){
                                $bookingProductDefaultSlot->repetition_sequence=json_decode($bookingProductDefaultSlot->repetition_sequence);
                            }
                        }
                        $bookingProductRental=$bookingProduct->rental_slot;
                        if($bookingProductRental){
                            if(is_string($bookingProductRental->slots)){
                                $bookingProductRental->slots=json_decode($bookingProductRental->slots);
                            }
                        }
                        $event_tickets=$bookingProduct->event_tickets;
                        if($event_tickets){
                            $event_tickets->tickets=json_decode($event_tickets->tickets);
                            $typeHelper = app(app('Webkul\BookingProduct\Helpers\Booking')->getTypeHepler($bookingProduct->type));

                            foreach($event_tickets->tickets as $ticket){

                                if ($typeHelper->isCartItemExceedNumberOfAvailableTickets(null,$bookingProduct,$ticket)) {
                                    $ticket->note='You cant Exceed Number Of Available Tickets ';
                                }

                                if ($typeHelper->isCartItemExceedMaximumTicketsPerBooking(null,$bookingProduct,$ticket)) {
                                    $ticket->note='You cant Exceed Maximum Tickets Per Booking';
                                }

                                if ($typeHelper->isCartItemExceedMaximumEventSize(null,$bookingProduct,$ticket)) {
                                    $ticket->note='You cant Exceed Maximum Event Size';
                                }
                            }

                        }
                        $rental=$bookingProduct->rental_slot;
                    }
                }
              /*end booking product logic*/
                $products = $this->productSellerHelper->getSellersProductsByProduct($product);
                $product->isInStock=isset($products[0]->quantity) ? $product->getTypeInstance()->haveSufficientQuantity(1,$products[0]->marketplace_seller_id) : false;
                $product->isWithinThirtyDays = strtotime($product->created_at) < strtotime('-30 days') ? 0 : 1;

                return view($this->_config['view'], compact('product', 'products', 'customer','customer_is_seller','bookingProduct','bookingProductDefaultSlot','bookingProductRental','event_tickets'));
            }

        }

        abort(404);
    }

    /**
     * @param $slug
     * @param string $secondarySlug
     * @param string $thirdSlug
     * @return Application|Factory|View
     */
    public function category($slug, $secondarySlug = '', $thirdSlug = '')
    {
        if(!isset($_GET['limit'])){
            return redirect(request()->fullUrlWithQuery(['limit' => 12]));
        }
        if (!empty($secondarySlug) && !is_array($secondarySlug)) {
            $slug .= '/'.$secondarySlug;
        }
        if (!empty($thirdSlug) && !is_array($thirdSlug)) {
            $slug .= '/'.$thirdSlug;
        }
        if (preg_match('/^([a-z0-9-]+\/?)+$/', $slug)) {

            if ($category = $this->categoryRepository->findByPath($slug)) {

                return view($this->_config['view'], compact('category'));
            }

        }
        abort(404);
    }

    /**
     * @param $seller
     * @param $slug
     * @return Application|Factory|View
     */
    public function sellerProduct($seller, $slug)
    {
        $customer_is_seller = false;
        if (auth()->guard('customer')->user()) {
            $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            if ($seller and $seller->is_approved == 1) {
                $customer_is_seller = true;
            }
        }
        $product = $this->productRepository->findBySlug($slug);
        $bookingProduct='';
        $bookingProductDefaultSlot='';
        $bookingProductRental ='';
        $event_tickets='';

        if ($product) {
            $product->child=$product->variants()->get();
            foreach ($product->child as $key => $child){
                $product->child[$key]->quantity=$product->child[$key]->totalQuantity();
            }
            if($product->type == 'booking'){
                $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->product_id);
                if($bookingProduct){
                    $bookingProductDefaultSlot=$bookingProduct->default_slot;
                    if($bookingProductDefaultSlot){
                        if(is_string($bookingProductDefaultSlot->slots)){
                            $bookingProductDefaultSlot->slots=json_decode($bookingProductDefaultSlot->slots);
                        }
                        if(is_string($bookingProductDefaultSlot->repetition_sequence)){
                            $bookingProductDefaultSlot->repetition_sequence=json_decode($bookingProductDefaultSlot->repetition_sequence);
                        }
                    }
                    $bookingProductRental=$bookingProduct->rental_slot;
                    if($bookingProductRental){
                        if(is_string($bookingProductRental->slots)){
                            $bookingProductRental->slots=json_decode($bookingProductRental->slots);
                        }
                    }
                    $event_tickets=$bookingProduct->event_tickets;
                    if($event_tickets){
                        $event_tickets->tickets=json_decode($event_tickets->tickets);
                        $typeHelper = app(app('Webkul\BookingProduct\Helpers\Booking')->getTypeHepler($bookingProduct->type));

                        foreach($event_tickets->tickets as $ticket){

                            if ($typeHelper->isCartItemExceedNumberOfAvailableTickets(null,$bookingProduct,$ticket)) {
                                $ticket->note='You cant Exceed Number Of Available Tickets ';
                            }

                            if ($typeHelper->isCartItemExceedMaximumTicketsPerBooking(null,$bookingProduct,$ticket)) {
                                $ticket->note='You cant Exceed Maximum Tickets Per Booking';
                            }

                            if ($typeHelper->isCartItemExceedMaximumEventSize(null,$bookingProduct,$ticket)) {
                                $ticket->note='You cant Exceed Maximum Event Size';
                            }
                        }

                    }
                    $rental=$bookingProduct->rental_slot;
                }
            }
            /*end booking product logic*/


            $products = $this->productSellerHelper->getSellersProductsByProduct($product);

            $collection = collect();
            foreach ($products as $item) {
                if($seller){
                    if ($item->marketplace_seller_id == $seller->id) {
                        $item->name = $product->name;
                        $collection->prepend($item);
                    }else {
                        $collection->push($item);
                    }
                }else{
                    $collection->push($item);
                }
            }

            $products = $collection;

            $customer = auth()->guard('customer')->user();

            return view($this->_config['view'], compact('product', 'products', 'customer', 'customer_is_seller','bookingProduct','bookingProductDefaultSlot','bookingProductRental','event_tickets'));
        }

        abort(404);
    }

}