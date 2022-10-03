<?php

namespace Devvly\FluidPayment\Http\Controllers;

use Devvly\FluidPayment\Models\FluidCustomer;
use Devvly\FluidPayment\Services\FluidApi;
use Exception;
use Illuminate\Http\RedirectResponse;
use Prettus\Validator\Exceptions\ValidatorException;
use Webkul\Checkout\Cart;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class PaymentController
{
    protected $orderRepository;

    protected $cartService;

    protected $fluidService;

    public function __construct(OrderRepository $orderRepository, Cart $cartService)
    {
        $this->orderRepository = $orderRepository;

        $this->cartService = $cartService;
    }

    /**
     * @return RedirectResponse
     * @throws ValidatorException
     * @throws Exception
     */
    public function createTransaction(): RedirectResponse
    {

        // Collect options
        $options = $this->collectOptionsForFluidService();
        $creds=null;
        if(isset($options['payment'])){
             if($options['payment']=='bluedog'){
                 $creds = FluidCustomer::query()->where('seller_id', $options['seller_id'])->where('type', 'bluedog-gateway')->first();
             }
         }

         if(!$creds){
             // Execute service
             $creds = FluidCustomer::query()->where('seller_id', $options['seller_id'])->where('type', 'seller-gateway')->first();
         }

        if(!$creds){
            $creds = FluidCustomer::query()->where('seller_id', $options['seller_id'])->where('type', '2acommerce-gateway')->first();
        }

        $result = (new FluidApi($creds->api_key, config('services.2acommerce.gateway_url')))->createTransaction($options);

        // Register order
        if ($result['status'] === 'success' || $result['status'] === 'pending_settlement') {
            $cartData = $this->cartService->prepareDataForOrder();
            if(!isset($result['data']['id'])){
                $message=$result['msg'];
                session()->flash('warning', $message);
                return redirect()->route('marketplace.cart.view');
            }
            // Add additional info to cartData
            $cartData['payment']['additional'] = json_encode([
                'fluid' => [
                    'transaction_id' => $result['data']['id']
                ],
            ]);

            $order = $this->orderRepository->create($cartData);

            $this->orderRepository->update(
                ['status' => 'processing'],
                $order->id
            );

            $this->cartService->deActivateCart();
            session()->flash('order', json_encode($order));
            if ($order->canInvoice()) {
                $invoiceData = [
                    'order_id' => $order->id,
                ];
                foreach ($order->items as $item) {
                    $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                }
                app(InvoiceRepository::class)->create($invoiceData);
            }
            session()->flash('success', 'Payment Successfull');
            return redirect()->route('shop.checkout.success');
        } else {
            if(isset($result['msg'])){
                if($result['msg']=='success'){
                    session()->flash('success', 'Payment Successfull');
                    return redirect()->route('shop.checkout.success');
                }else{
                    $message=$result['msg'];
                }
            }else{
                $message='There was an error processing your transaction, please try again. If you continue to have problems please contact us.';
            }

            return redirect()->route('shop.checkout.onepage.index', ['sellerId' => $options['seller_id'],'message' =>$message]);
        }
    }

    /**
     * @return array
     */
    protected function collectOptionsForFluidService(): array
    {
        if (!session()->has('fluid_payment')) return [];

        $paymentMethod = [];
        $shippingInfo = [];
        $billingInfo = [];
        $fluidPaymentInfo = session()->get('fluid_payment');
        if ($fluidPaymentInfo['type'] === 'token') {
            $paymentMethod['token'] = $fluidPaymentInfo['data']['token'];
        } elseif ($fluidPaymentInfo['type'] === 'card') {
            $paymentMethod['customer'] = $fluidPaymentInfo['data']['card'];
        }
        if(isset($fluidPaymentInfo['payment'])){
            if($fluidPaymentInfo['payment']=='bluedog'){
                $fluidPaymentInfo['payment']='bluedog';
            }
        }
        session()->forget('fluid_payment');

        $cart = $this->cartService->getCart($fluidPaymentInfo['sellerId']);
        $shippingAddress = $cart->getShippingAddressAttribute();
        if ($shippingAddress) {
            $shippingInfo = [
                'first_name' => $shippingAddress->first_name,
                'last_name' => $shippingAddress->last_name,
                'address_line_1' => $shippingAddress->address1,
                'address_line_2' => $shippingAddress->address2,
                'city' => $shippingAddress->city,
                'state' => $shippingAddress->state,
                'postal_code' => $shippingAddress->postcode,
                'country' => $shippingAddress->country,
                'email' => $shippingAddress->email,
                'phone' => $shippingAddress->phone,
            ];
        }
        $billingAddress = $cart->getBillingAddressAttribute();
        if ($billingAddress) {
            $billingInfo = [
                'first_name' => $billingAddress->first_name,
                'last_name' => $billingAddress->last_name,
                'address_line_1' => $billingAddress->address1,
                'address_line_2' => $billingAddress->address2,
                'city' => $billingAddress->city,
                'state' => $billingAddress->state,
                'postal_code' => $billingAddress->postcode,
                'country' => $billingAddress->country,
                'email' => $billingAddress->email,
                'phone' => $billingAddress->phone,
            ];
        }

        return [
            'seller_id' => $fluidPaymentInfo['sellerId'],
            'card_nickname' => $fluidPaymentInfo['data']['nickname'] ?? '',
            'customer_id' => auth()->guard('customer')->user()->id,
            'type' => 'sale',
            'amount' => (int)(number_format($cart->base_grand_total, 2, '.', '') * 100), // in cents
            'tax_amount' => (int)$cart->base_tax_total,
            'currency' => $cart->global_currency_code,
            'order_id' => (string)$cart->id,
            'ip_address' => isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] :  $this->getRealIpAddr(),
            'create_vault_record' => isset($paymentMethod['token']),
            'payment_method' => $paymentMethod,
            'shipping_address' => !empty($shippingInfo) ? $shippingInfo : null,
            'billing_address' => !empty($billingInfo) ? $billingInfo : null,
            'payment' => isset($fluidPaymentInfo['payment']) ? $fluidPaymentInfo['payment'] : ''
        ];
    }
    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}