<?php

namespace Webkul\Stripe\Http\Controllers;


use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Stripe\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Stripe\Repositories\StripeRepository;
use Stripe\Stripe as Stripe;
use Webkul\Stripe\Helpers\Helper;
use Illuminate\Support\Facades\Session as serverSession;


/**
 * StripeConnect Controller
 *
 * @author  shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class StripeConnectController extends Controller
{
    protected $cart;

    protected $order;

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * StripeRepository object
     *
     * @var array
     */
    protected $stripeRepository;

    /**
     * To hold the Test stripe secret key
     */
    protected $stripeSecretKey = null;

    /**
     * Determine test mode
     */
    protected $testMode;

    /**
     * Determine if Stripe is active or Not
     */
    protected $active;

    /**
     * Statement descriptor string
     */
    protected $statementDescriptor;



    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * Helper object
     *
     * @var object
     */
    protected $helper;

    protected $appName;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  Webkul\StripeConnect\Repositories\StripeRepository  $stripeRepository
     * @param  Webkul\StripeConnect\Helpers\Helper  $helper
     *
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        stripeRepository $stripeRepository,
        Helper $helper,
        \Webkul\Checkout\Cart $cartService
    )
    {
        $this->cartService = $cartService;

        $this->helper = $helper;

        $this->orderRepository = $orderRepository;

        $this->stripeRepository = $stripeRepository;



        $this->testMode = core()->getConfigData('sales.paymentmethods.stripe.debug');
        $url=\Request::url();
        $x=explode("/",$url);
        $sellerId=$x[sizeof($x) - 1];
        if(isset(\Request()->all()['sellerId'])){
            $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where(['seller_id' => \Request()->all()['sellerId']]);
            if($stripeCustomer){
                $this->stripeSecretKey= $stripeCustomer->first()->api_key;
            }
            $this->sellerId=\Request()->all()['sellerId'];
        }elseif(session()->has('stripe_payment')) {
            $stripePaymentInfo = session()->get('stripe_payment');
            $sellerId=$stripePaymentInfo['sellerId'];
            $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where(['seller_id' => $sellerId]);
            if($stripeCustomer){
                $this->stripeSecretKey= $stripeCustomer->first()->api_key;
            }
            $this->sellerId=$sellerId;
        }else {
            if ($sellerId && is_numeric($sellerId)) {
                $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where(['seller_id' => $sellerId]);
                if ($stripeCustomer) {
                    $this->stripeSecretKey = $stripeCustomer->first()->api_key;
                }
                $this->sellerId=$sellerId;
            }
        }
        $this->appName = '2AGunSow Stripe Payment Gateway';

        $this->partner_Id = 'pp_partner_FLJSvfbQDaJTyY';

        Stripe::setApiVersion("2019-12-03");

        Stripe::setAppInfo(
            $this->appName,
            env('APP_VERSION'),
            env('APP_URL'),
            $this->partner_Id
        );

        stripe::setApiKey($this->stripeSecretKey);

    }

    /**
     * Save card after payment using new card.
     *
     * @return Json
     */
    public function saveCard()
    {

        try {

            $data=request()->all();
            if(!isset($data['result']['customerResponse'])){
                $customerResponse = \Stripe\Customer::create(["description" => "Customer for " . auth()->guard('customer')->user()->email, "source" => request()->stripetoken]);
                $payment_method = \Stripe\PaymentMethod::retrieve(request()->paymentMethodId);
                $attachedCustomer = $payment_method->attach(['customer' => $customerResponse->id]);
                $last4 = request()->result['paymentMethod']['card']['last4'];
                $response = ['customerResponse' => $customerResponse, 'attachedCustomer' => $attachedCustomer,];
                $expiration_date=request()->result['paymentMethod']['card']['exp_month'].'/'. substr(request()->result['paymentMethod']['card']['exp_year'], -2);
            }else{
                $customerResponse =(object) $data['result']['customerResponse'];
                $payment_method = \Stripe\PaymentMethod::retrieve(request()->paymentMethodId);
                $attachedCustomer = $payment_method->attach(['customer' => $customerResponse->id]);
                $last4 = request()->result['attachedCustomer']['card']['last4'];
                $response = ['customerResponse' => $customerResponse, 'attachedCustomer' => $attachedCustomer,];
                $expiration_date=request()->result['attachedCustomer']['card']['exp_month'].'/'. substr(request()->result['attachedCustomer']['card']['exp_year'], -2);
            }
            // Create if it is first time when user uses this card

            $getStripeRepository = $this->stripeRepository->findOneWhere(['expiration_date' => $expiration_date , 'last_four' => $last4, 'customer_id' => auth()->guard('customer')->user()->id,]);
            if (!isset($getStripeRepository)) {
                $data=[
                    'nickname' => request()->nickname,
                    'last_four' => $last4,
                    'expiration_date' => $expiration_date,
                    'token' => request()->stripetoken,
                    'misc' => json_encode($response),
                    'stripe_card_id' => $customerResponse->values()[6],
                    'stripe_customer_id' => $customerResponse->values()[0],
                    'customer_id' => auth()->guard('customer')->user()->id,
                    'seller_id' => $this->sellerId
                ];
                $this->stripeRepository->create($data);
                return response()->json(['code' =>200,'message' =>'success','last_four'=>$last4],200);
            }
            return response()->json(['code' =>200,'message' =>'Card Already found'],200);
        } catch(\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>$e->getError()->message];
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>$e->getError()->message];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>$e->getError()->message];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>$e->getError()->message];
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>$e->getError()->message];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>$e->getError()->message];
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            session()->flash('error', $e->getError()->message);
            return ['code' =>500,'message' =>trans('stripe::app.something-went-wrong')];
        }
    }

    /**
     * Generate payment using saved card
     *
     * @return Json
     */
    public function savedCardPayment()
    {
        try {
            $selectedId = request()->savedCardSelectedId;

            $savedCard = $this->stripeRepository->findOneWhere([
                'id' => $selectedId,
            ]);

            $miscDecoded = json_decode($savedCard->misc);

            $payment = $this->helper->productDetail();

            $stripeId = '';

            $customerId = $miscDecoded->customerResponse->id;

            $paymentMethodId = $miscDecoded->attachedCustomer->id;

            $savedCardPayment = $this->helper->stripePayment($payment,$stripeId,$paymentMethodId,$customerId);

            if ($savedCard) {
                return response()->json([
                    'customer_id' => $miscDecoded->customerResponse->id,
                    'payment_method_id' => $miscDecoded->attachedCustomer->id,
                    'savedCardPayment' => $savedCardPayment,
                ]);
            } else {
                return response()->json(['sucess' => 'false'],404);
            }
        } catch(Exception $e) {
            throw $e;
        }
    }



    /**
     * Prepares order's
     *
     * @return json
     */
    public function createCharge()
    {
        if (!empty($this->stripeSecretKey)) {
            if (session()->has('stripe_payment')) {
                $stripePaymentInfo = session()->get('stripe_payment');
                $cart=$this->cartService->getCart($stripePaymentInfo['sellerId']);
                $stripeCard = $this->stripeRepository->findWhere(['customer_id' => auth()->guard('customer')->user()->id])->sortByDesc('id')->first();
                $misc = json_decode($stripeCard->misc);
                $stripeCustomerId = $misc->customerResponse->id;
                $stripe = \Stripe\Stripe::setApiKey($this->stripeSecretKey);
                $stripe = \Stripe\Charge::create(array("amount" => (int) round(($cart->base_grand_total * 100), 0) , "currency" => "usd", "customer" => $stripeCustomerId, "description" => "Transaction For customer " . auth()->guard('customer')->user()->id));
                $result['status'] = $stripe->values()[43];
                // Register order
                if ($result['status'] === 'succeeded') {
                    $cartData = $this->cartService->prepareDataForOrder();
                    // Add additional info to cartData
                    $cartData['payment']['additional'] = json_encode(['stripe' => ['transaction_id' => $stripe->values()[8],'charge' => $stripe->values()[0]]]);
                    $order = $this->orderRepository->create($cartData);
                    $this->orderRepository->update(['status' => 'processing'], $order->id);
                    $this->cartService->deActivateCart();
                    session()->flash('order', $order);
                    if ($order->canInvoice()) {
                        $invoiceData = ['order_id' => $order->id,];
                        foreach ($order->items as $item) {
                            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                        }
                        app(InvoiceRepository::class)->create($invoiceData);
                    }
                    session()->flash('success', 'Payment Successfull');
                    return redirect()->route('shop.checkout.success');
                } else {
                    if (isset($result['msg'])) {
                        $message = $result['msg'];
                    } else {
                        $message = 'There was an error processing your transaction, please try again. If you continue to have problems please contact us.';
                    }
                    session()->flash('warning', $message);
                    return redirect()->route('marketplace.cart.view');
                }
            } else {
                $message = 'There was an error processing your transaction, please try again. If you continue to have problems please contact us.';
                session()->flash('warning', $message);
                return redirect()->route('marketplace.cart.view');
            }
        } else {
            return view('marketplace.cart.view');
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    public function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Delete the selected stripe card
     *
     * @return string
     */
    public function deleteCard()
    {
        $deleteIfFound = $this->stripeRepository->findOneWhere(['id' => request()->input('id'), 'customer_id' => auth()->guard('customer')->user()->id]);

        $result = $deleteIfFound->delete();

        return (string)$result;
    }

    /**
     * On payment cancel
     *
     * @return response
     */
    public function paymentCancel()
    {
        session()->flash('error', trans('stripe::app.payment-failed'));

        return response()->json([
            'data' => [
                'route' => route("shop.checkout.cart.index"),
                'success' => true
            ]
        ]);
    }

    public function getCustomerCards(){
        $cards = collect();
        $customer_id = auth()->guard('customer')->user()->id;
        $cards = app('Webkul\Stripe\Repositories\StripeRepository')->findWhere(['customer_id' => $customer_id]);
        return $cards;

    }

    public function getStripeMode($sellerId){
        $key='';

        $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where(['seller_id' => $sellerId]);

        if($stripeCustomer){
            $key= $stripeCustomer->first()->public_key;
        }

        return response()->json([
            'key' => $key,
            'success' => true

        ]);
    }
}
