<?php

namespace Devvly\ClearentPayment\Http\Controllers;

use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Models\SaleOptions;
use Devvly\Clearent\Models\Transaction;
use Devvly\Clearent\Resources\Resources;
use Devvly\ClearentPayment\Repositories\ClearentCardRepository;
use Devvly\ClearentPayment\Repositories\ClearentCartRepository;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class PaymentController extends Controller
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
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     * @var array
     */
    protected $invoiceRepository;


    /**
     * ClearentCardRepository object
     *
     * @var array
     */
    protected $cardRepository;

    /**
     * ClearentCartRepository object
     *
     * @var array
     */
    protected $cartRepository;

    /** @var Resources */
    protected $resources;

    /**
     * PaymentController constructor.
     *
     * @param OrderRepository $orderRepository
     * @param InvoiceRepository $invoiceRepository
     * @param ClearentCardRepository $cardRepository
     * @param ClearentCartRepository $cartRepository
     * @param Resources $resources
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository,
        ClearentCardRepository $cardRepository,
        ClearentCartRepository $cartRepository,
        Resources $resources
    )
    {

        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->cardRepository = $cardRepository;

        $this->cartRepository = $cartRepository;
        $this->resources = $resources;
        $this->cart = Cart::getCart();
    }

    public function createCart()
    {
        $selectedCard = request()->input('savedCardSelectedId');
        $card = $this->cardRepository->find($selectedCard);
        /** @var \Devvly\ClearentPayment\Payment\ClearentPayment $payment */
        $payment = app('Devvly\ClearentPayment\Payment\ClearentPayment');
        $cart = $payment->getCart();
        $result = $this->cartRepository->findOneWhere(
            [
                'cart_id' => $cart->id,
                'card_id' => $card->id,
            ]
        );
        if (!$result) {
            $result = $this->cartRepository->create(
                [
                    'cart_id' => $cart->id,
                    'card_id' => $card->id,
                ]
            );
        }

        if ($result) {
            return response()->json(['success' => true, 'result' => $result]);
        } else {
            return response()->json(['success' => false], 400);
        }
    }

    public function createCharge(Request $request)
    {
        /** @var \Webkul\SAASCustomizer\Models\Checkout\Cart $cart */
        $cart = Cart::getCart();
        $address = $cart->billing_address;
        $clearentCard = $this->cartRepository
            ->orderBy('id', 'DESC')
            ->with('card')
            ->findOneWhere(['cart_id' => $cart->id]);
        if (!$clearentCard) {
            session()->flash('warning', 'No card found');
            return redirect()->route('shop.checkout.onepage.index');
        }
        $card = $clearentCard->card()->first();
        $total = number_format($cart->base_grand_total, 2, '.', '');
        $options = new SaleOptions();
        if (auth()->guard('customer')->check() && $card['save']) {
            $options->setCard($card->card_token);
            $options->setExpDate($card->exp_date);
        } else {
            $options->setCreateCardToken(false);
            $options->setJwtToken($card->jwt_token);
            $options->setCardType($card->card_type);
            $options->setZipCode($address->postcode);
        }
        $options->setAmount($total);
        $res = $this->resources->transactions()->sale($options);
        if ($res instanceof Transaction && $res->getResult() === "APPROVED") {
            $this->order = $this->orderRepository->create(Cart::prepareDataForOrder());
            $this->orderRepository->update(
                ['status' => 'processing'],
                $this->order->id
            );

            Cart::deActivateCart();

            // Delete the jwt token if the user doesn't want to save the card:
            if (!$card['save']) {
                $this->cardRepository->delete($card->id);
            }
            session()->flash('order', $this->order);
            if ($this->order->canInvoice()) {
                $this->invoiceRepository->create($this->prepareInvoiceData());
            }
            session()->flash('success', 'Payment Successfull');
            return redirect()->route('shop.checkout.success');
        } else if ($res instanceof Error) {
            $message = $res->getErrorMessage();
            if ($message === "Invalid or expired JWT") {
                // delete the card because it's useless:
                $this->cardRepository->delete($card->id);
            }
            session()->flash('warning', 'The card on file is no longer valid. Please try to checkout again and re-add it.');
            return redirect()->route('shop.checkout.cart.index');
        } else {
            session()->flash('warning', 'Failed to checkout. Please contact support.');
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id,
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Call to delete saved card
     *
     *
     * @return string
     */

    public function deleteCard()
    {
        $deleteIfFound = $this->cardRepository->findOneWhere(
            [
                'id' => request()->input('id'),
                'customers_id' => auth()->guard('customer')->user()->id,
            ]
        );

        $result = $deleteIfFound->delete();

        return (string)$result;
    }

}
