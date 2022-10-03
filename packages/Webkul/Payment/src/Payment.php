<?php

namespace Webkul\Payment;

use Devvly\FluidPayment\Models\FluidCustomer;
use Illuminate\Support\Facades\Config;
use Webkul\Marketplace\Models\Seller;

class Payment
{

    /**
     * Returns all supported payment methods
     *
     * @return array
     */
    public function getSupportedPaymentMethods()
    {
        $paymentMethods = $this->getPaymentMethods();

        return [
            'jump_to_section' => 'payment',
            'paymentMethods'  => $this->getPaymentMethods(),
            'html'            => view('shop::checkout.onepage.payment', compact('paymentMethods'))->render(),
        ];
    }

    /**
     * Returns all supported payment methods
     *
     * @param $sellerId
     * @return array
     */
    public function getPaymentMethods($sellerId = false)
    {
        $sellerPaymentMethods=[];
        if ($sellerId !== false) {
            $seller = Seller::query()->find($sellerId);
            if (!$seller->payment_methods) return [];
            $sellerPaymentMethods = explode(',', $seller->payment_methods);
        }

        $paymentMethods = [];
        if(in_array('bluedog',$sellerPaymentMethods)){
           array_push($paymentMethods, [
                'method'       => 'bluedog',
                'method_title' => 'bluedog',
                'description'  =>'BlueDog is a merchant account provider that serves most business types, including some high-risk industries. The company was founded in 2010 by an original co-founder of Eliot Management Group. BlueDog is a reseller of First American Payment Systems and First Data (now Fiserv) merchant accounts as well as Clover point-of-sale products.',
                'sort'         => 5,
            ]);
        }
        if(in_array('seller-fluid',$sellerPaymentMethods)){
            array_push($paymentMethods, [
                'method'       => 'seller-fluid',
                'method_title' => 'Fluidpay',
                'description'  =>'Fluidpay is the first completely cloud-based payment gateway with level 1 PCI compliance and non-compete guarantee.',
                'sort'         => 6,
            ]);
        }

        foreach (Config::get('paymentmethods') as $paymentMethod) {
            $object = app($paymentMethod['class']);
            $code = $object->getCode();

            if ($sellerId !== false) {
                if (!in_array($code, $sellerPaymentMethods)) continue;
                if ($code === 'fluid' && !FluidCustomer::query()
                        ->where([['seller_id', '=', $sellerId], ['is_approved', '=', 1]])->first()) {
                    continue;
                }
            }

            if ($object->isAvailable()) {
                $paymentMethods[] = [
                    'method'       => $object->getCode(),
                    'method_title' => $object->getTitle(),
                    'description'  => $object->getDescription(),
                    'sort'         => $object->getSortOrder(),
                ];
            }
        }

        usort ($paymentMethods, function($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }

            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });

        return $paymentMethods;
    }

    /**
     * Returns payment redirect url if have any
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return string
     */
    public function getRedirectUrl($cart)
    {
        if($cart->payment->method=='bluedog' || $cart->payment->method=='seller-fluid'){
            $cart->payment->method='fluid';
        }
        $payment = app(Config::get('paymentmethods.' . $cart->payment->method . '.class'));

        return $payment->getRedirectUrl();
    }
}