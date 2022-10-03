<?php

namespace Webkul\Authorize\Http\Controllers;

use Webkul\Authorize\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Authorize\Repositories\AuthorizeRepository;

use Webkul\Authorize\Helpers\Helper;


/**
 * AuthorizeConnectController Controller
 *
 * @author  shaiv roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AuthorizeConnectController extends Controller
{
     /**
     * Cart object
     *
     * @var array
     */
    protected $cart;

     /**
     * Order object
     *
     * @var array
     */
    protected $order;

    /**
     * Helper object
     *
     * @var array
     */
    protected $helper;

     /**
     * authorizeRepository object
     *
     * @var array
     */
    protected $authorizeRepository;

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;



    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     *
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        AuthorizeRepository $authorizeRepository,
        Helper $helper
    )
    {

        $this->orderRepository = $orderRepository;

        $this->authorizeRepository = $authorizeRepository;


        $this->helper = $helper;

        $this->cart = Cart::getCart();

    }

    public function collectToken()
    {

        if (!request()->input('savedCardSelectedId')) {
            $misc = request()->input('response');
            if (auth()->guard('customer')->check()) {
                $last4 =substr($misc['encryptedCardData']['cardNumber'],-4);
                $cardExist = $this->authorizeRepository->findOneWhere([
                    'last_four' => $last4,
                    'customer_id' => auth()->guard('customer')->user()->id,
                ]);
                if ($cardExist) {
                    $result = $cardExist->update([
                        'token' => $misc['opaqueData']['dataValue'],
                        'misc' => json_encode($misc),
                    ]);
                } else {
                    $result = $this->authorizeRepository->create([
                        'customer_id' => auth()->guard('customer')->user()->id,
                        'expiration_date' => $misc['encryptedCardData']['expDate'],
                        'nickname' =>$misc['customerInformation']['firstName'],
                        'token' => $misc['opaqueData']['dataValue'],
                        'authorize_token' => $misc['opaqueData']['dataValue'],
                        'last_four' => $last4,
                        'misc' => json_encode($misc),
                    ]);
                }


                if ($result) {
                    return response()->json(['success' => 'true']);
                } else {
                    return response()->json(['success' => 'false'], 400);
                }
            }

        }
    }

    public function createCharge()
    {
        if (session()->has('authorize_payment')) {
            $authorizePaymentInfo = session()->get('authorize_payment');
            $cart=Cart::getCart($authorizePaymentInfo['sellerId']);
            $authorizeCard = $this->authorizeRepository->findWhere(['customer_id' => auth()->guard('customer')->user()->id])->sortByDesc('id')->first();
            $authorizeCardDecode = json_decode($authorizeCard->misc);
            if (auth()->guard('customer')->check()) {


                if ( isset($authorizeCardDecode->customerResponse)) {

                    $savedCardPaymentResponse = $this->helper->chargeCustomerProfile($authorizeCardDecode);
                    $savedCardPaymentResponse->last_four=$authorizeCard->last_four;
                    $savedCardPaymentResponse->expiration_date=$authorizeCard->expiration_date;
                    $customerProfileResponse = $this->helper->paymentResponse($savedCardPaymentResponse);

                    if ($customerProfileResponse == 'true') {

                        return redirect()->route('shop.checkout.success');

                    } else {

                        session()->flash('warning', $customerProfileResponse);

                        return redirect()->route('shop.checkout.cart.index');
                    }

                } else {

                    $customerEmail = Cart::getCart()->billing_address->email;

                    $cutomerProfileResponse = $this->helper->createCustomerProfile($customerEmail,$authorizeCardDecode);

                    if (($cutomerProfileResponse != null) && ($cutomerProfileResponse->getMessages()->getResultCode() == "Ok")) {

                        $paymentProfiles = $cutomerProfileResponse->getCustomerPaymentProfileIdList();

                        $customerResponse = [
                            'customerProfileId' => $cutomerProfileResponse->getCustomerProfileId(),
                            'paymentProfielId' => $paymentProfiles[0],
                        ];

                        $cardToken = $this->authorizeRepository->findOneWhere([
                            'token' => $authorizeCardDecode->opaqueData->dataValue,
                        ])->misc;


                        $cardTokenDecode = json_decode($cardToken);

                        $updateRespone = [
                            'cardResponse' => $authorizeCardDecode,
                            'customerResponse' => $customerResponse,
                        ];

                        $this->authorizeRepository->findOneWhere([
                            'token' => $authorizeCardDecode->opaqueData->dataValue,
                        ])->update([
                            'misc' => json_encode($updateRespone),
                        ]);

                        $UpdatedToken = $this->authorizeRepository->findOneWhere([
                            'token' => $authorizeCardDecode->opaqueData->dataValue,
                        ])->misc;

                        $decodeUpdatedToken = json_decode($UpdatedToken);

                        $savedCardPaymentResponse = $this->helper->chargeCustomerProfile($decodeUpdatedToken);
                        $savedCardPaymentResponse->last_four=$authorizeCard->last_four;
                        $savedCardPaymentResponse->expiration_date=$authorizeCard->expiration_date;
                        $customerProfileResponse = $this->helper->paymentResponse($savedCardPaymentResponse);

                        if ($customerProfileResponse == 'true') {

                            return redirect()->route('shop.checkout.success');

                        } else {

                            session()->flash('warning', $customerProfileResponse);

                            return redirect()->route('shop.checkout.cart.index');
                        }

                    } else {

                        $this->helper->deleteCart();

                        $errorMessages = $cutomerProfileResponse->getMessages()->getMessage();

                        session()->flash('warning', $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText());

                        return redirect()->route('shop.checkout.cart.index');
                    }
                }
            } else {


                $guestResponse = $this->helper->createAnAcceptPaymentTransaction($authorizeCardDecode);
                $guestResponse->last_four=$authorizeCard->last_four;
                $guestResponse->expiration_date=$authorizeCard->expiration_date;

                $paymentResponse = $this->helper->paymentResponse($guestResponse);

                if ($paymentResponse == 'true') {

                    return redirect()->route('shop.checkout.success');

                } else {

                    $this->helper->deleteCart();

                    session()->flash('warning', $paymentResponse);

                    return redirect()->route('shop.checkout.cart.index');
                }
            }
        }

    }

     /**
     * Call to delete saved card
     *
     *
     * @return string
     */

    public function deleteCard()
    {
        $deleteIfFound = $this->authorizeRepository->findOneWhere(['id' => request()->input('id'), 'customer_id' => auth()->guard('customer')->user()->id]);

        $result = $deleteIfFound->delete();

        return (string)$result;
    }
    public function getAuthorizeMode($sellerId){
        $api_login_id='';
        $transaction_key='';
        $authorizeCustomer = app(\Webkul\Authorize\Models\AuthorizeCustomer::class)->where(['seller_id' => $sellerId]);
        if($authorizeCustomer){
            $api_login_id= $authorizeCustomer->first()->api_key;
            $transaction_key= $authorizeCustomer->first()->public_key;
            return response()->json([
                'api_login_id' => $api_login_id,
                'transaction_key' => $transaction_key,
                'code' => 200
            ],200);
        }

        return response()->json([
            'api_login_id' => $api_login_id,
            'transaction_key' => $transaction_key,
            'code' => 500
        ],200);
    }

    public function getCards(){
        $cards = collect();
        $customer_id = auth()->guard('customer')->user()->id;
        $cards = app('Webkul\Authorize\Repositories\AuthorizeRepository')->findWhere(['customer_id' => $customer_id]);
        return $cards;
    }

}
