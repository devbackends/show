<?php
namespace Webkul\Stripe\Helpers;
use Webkul\Checkout\Facades\Cart;
use Stripe\Charge as StripeCharge;
use Webkul\Stripe\Repositories\StripeRepository;
use Stripe\PaymentIntent as PaymentIntent;

class Helper {

    /**
     * StripeRepository object
     *
     * @var array
     */
    protected $stripeRepository;


    public function __construct(stripeRepository $stripeRepository)
    {
        $this->stripeRepository = $stripeRepository;

    }

    /**
     * Seperate seller according to their product
     *
     *
     * @return array
     */
    public function productDetail()
    {
        return null;
    }

     /**
     * Create payment for stripe
     *
     *
     * @return boolean
     */
    public function stripePayment($payment='',$stripeId = '',$paymentMethodId='',$customerId = '')
    {

        try {
            
            if  ($customerId != '') {
                $result = PaymentIntent::create([
                    "amount" =>round(Cart::getCart()->base_grand_total, 2) * 100,
                    "customer" => $customerId,
                    "currency" => core()->getBaseCurrencyCode(),
                    "receipt_email" => Cart::getCart()->customer_email,
                ]); 

            } else {
                $result = PaymentIntent::create([
                    "amount" =>round(Cart::getCart()->base_grand_total, 2) * 100,
                    "currency" => core()->getBaseCurrencyCode(),
                    "payment_method_types" => ["card"],
                    "receipt_email" => Cart::getCart()->customer_email,
                ]);
            }
        } catch (\Exception $e) {
            return $e;
        }   
       

        return $result;
    }

    public function deleteCardIfPaymentNotDone($getCartDecode)
    {
        if (isset ($getCartDecode->stripeReturn->last4)) {
            $this->stripeRepository->deleteWhere([
                'last_four' => $getCartDecode->stripeReturn->last4
            ]);
        }
    }
}