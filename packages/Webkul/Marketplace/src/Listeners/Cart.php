<?php

namespace Webkul\Marketplace\Listeners;

use Exception;
use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository as CoreProductRepository;
use Cart as CartFacade;

/**
 * Cart event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart
{
    /**
     * SellerRepository object
     *
     * @var Seller
    */
    protected $sellerRepository;

    /**
     * ProductFlatRepository object
    */
    protected $productFlatRepository;

    /**
     * CoreProductRepository Object
     */
    protected $coreProductRepository;




    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository  $sellerRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository $productFlatRepository
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        ProductFlatRepository $productFlatRepository,
        CoreProductRepository $coreProductRepository
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->coreProductRepository = $coreProductRepository;
    }

    /**
     * Product added to the cart
     *
     * @param $productId
     * @throws Exception
     */
    public function cartItemAddBefore($productId)
    {
        $data = request()->all();
        if (isset($data['seller_info']) && !$data['seller_info']['is_owner']) {
            $productFlat = $this->productFlatRepository->findWhere(['product_id'=>$data['seller_info']['product_id']])->first();
        } else {
            if (isset($data['selected_configurable_option'])) {
                $productFlat = $this->productFlatRepository->findOneWhere([
                        'product_id' => $data['selected_configurable_option']
                    ]);
            } else {
                $productFlat = $this->productFlatRepository->findOneWhere([
                        'product_id' => $productId
                    ]);

            }
        }



        if (!$productFlat || $productFlat->product->type === 'booking') {
            return;
        }

        if (!isset($data['quantity']))
            $data['quantity'] = 1;

        if ($cart = app(\Webkul\Checkout\Cart::class)->getCart()) {
            $cartItem = $cart->items()->where('product_id', $productFlat->product_id)->first();

            if ($cartItem) {


                if (!$productFlat->product->haveSufficientQuantity($data['quantity']))
                    throw new Exception('Requested quantity not available.');

                $quantity = $cartItem->quantity + $data['quantity'];
            } else {
                $quantity = $data['quantity'];
            }
        } else {
            $quantity = $data['quantity'];
        }

        if (!$productFlat->product->haveSufficientQuantity($quantity)) {
            throw new Exception('Requested quantity not available.');
        }
    }

    /**
     * Product added to the cart
     *
     */
    public function cartItemAddAfter()
    {
        foreach(app(\Webkul\Checkout\Cart::class)->getCart()->items as $items)
        {
            if (isset($items->additional['seller_info']) && !$items->additional['seller_info']['is_owner']) {
                $productFlat = $this->productFlatRepository->findWhere(['product_id'=>$items->additional['seller_info']['product_id']])->first();

                if ($productFlat) {
                    $items->price = core()->convertPrice($productFlat->price);
                    $items->base_price = $productFlat->price;
                    $items->custom_price = $productFlat->price;
                    $items->total = core()->convertPrice($productFlat->price * $items->quantity);
                    $items->base_total = $productFlat->price * $items->quantity;

                    $items->save();
                }
                $items->save();
            } else {
                $items->save();
            }
        }


    }
}
