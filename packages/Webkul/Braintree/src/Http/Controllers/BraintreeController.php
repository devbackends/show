<?php

namespace Webkul\Braintree\Http\Controllers;

use Illuminate\Http\Request;
use Braintree_Gateway;
use Braintree_Transaction;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Checkout\Facades\Cart;
use App\Exceptions\Handler;


class BraintreeController extends Controller
{

    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Redirects to the Braintree.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        $merchentId = core()->getConfigData('sales.paymentmethods.braintree.braintree_merchant_id');
        $privateKey = core()->getConfigData('sales.paymentmethods.braintree.braintree_private_key');
        $publicKey = core()->getConfigData('sales.paymentmethods.braintree.braintree_public_key');

        if($merchentId && $privateKey && $publicKey)
        {
            try {
            $clientToken = $this -> generateClientToken();

            return view('braintree::drop-in-ui', compact('clientToken'));
            }
            catch(\Exception $e)
            {
                return redirect()->back();
            }
        }
        else {
           return redirect()->back();
        }
    }

    /**
     * Generate client token
     *
     * @return Mixed
     */
    public function generateClientToken()
    {
        $customerId = null;

        if(core()->getConfigData('sales.paymentmethods.braintree.debug') == '1')
            { $debug = 'sandbox'; }
        else
           { $debug = 'production'; }

        $gateway = new Braintree_Gateway([
            'environment' => $debug,
            'merchantId' => core()->getConfigData('sales.paymentmethods.braintree.braintree_merchant_id'),
            'publicKey' => core()->getConfigData('sales.paymentmethods.braintree.braintree_public_key'),
            'privateKey' => core()->getConfigData('sales.paymentmethods.braintree.braintree_private_key')
        ]);

        $clientToken = $gateway->clientToken()->generate([
            "customerId" => $customerId
        ]);

        return $clientToken;
    }

    /**
     * Perform the transaction
     *
     * @return response
     */
    public function transaction(Request $request)
    {
        if(core()->getConfigData('sales.paymentmethods.braintree.debug') == '1')
            { $debug = 'sandbox'; }
        else
           { $debug = 'production'; }

        $gateway = new Braintree_Gateway([
            'environment' => $debug,
            'merchantId' => core()->getConfigData('sales.paymentmethods.braintree.braintree_merchant_id'),
            'publicKey' => core()->getConfigData('sales.paymentmethods.braintree.braintree_public_key'),
            'privateKey' => core()->getConfigData('sales.paymentmethods.braintree.braintree_private_key')
        ]);

        $clientToken = $gateway->clientToken()->generate();

        $cartAmount = \Cart::getCart()->base_grand_total;

        $payload = $request->input('payload', false);
        $nonceFromTheClient = $payload['nonce'];

        $result = $gateway->transaction()->sale([
            'amount' => $cartAmount,
            'paymentMethodNonce' => $nonceFromTheClient,
            'options' => [
              'submitForSettlement' => True
            ]
          ]);

        if($result->success == 'true') {

            $order = $this->orderRepository->create(Cart::prepareDataForOrder());

            $this->order = $this->orderRepository->findOneWhere([
                'cart_id' => Cart::getCart()->id
                ]);

            $this->orderRepository->update(['status' => 'processing'], $this->order->id);

            Cart::deActivateCart();

            session()->flash('order', $order);

            $this->invoiceRepository->create($this->prepareInvoiceData());

            session()->flash('success', 'Payment Successfull');

        }
        // return redirect()->route('shop.checkout.success');
        return response()->json($result);

    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

}
