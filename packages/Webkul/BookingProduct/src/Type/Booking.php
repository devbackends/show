<?php

namespace Webkul\BookingProduct\Type;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Helpers\ProductImage;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Helpers\Booking as BookingHelper;
use Webkul\Product\Type\Virtual;

class Booking extends Virtual
{
    /**
     * BookingProductRepository instance
     *
     * @var BookingProductRepository
     */
    protected $bookingProductRepository;

    /**
     * Booking helper instance
     *
     * @var \Webkul\BookingProduct\Helpers\Booking
     */
    protected $bookingHelper;

    /**
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.channels',
        'bookingproduct::admin.catalog.products.accordians.booking',
        'admin::catalog.products.accordians.product-links',
    ];

    /**
     * Create a new product type instance.
     *
     * @param AttributeRepository $attributeRepository
     * @param ProductRepository $productRepository
     * @param ProductAttributeValueRepository $attributeValueRepository
     * @param ProductImageRepository $productImageRepository
     * @param ProductImage $productImageHelper
     * @param BookingProductRepository $bookingProductRepository
     * @param BookingHelper $bookingHelper
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,
        ProductImageRepository $productImageRepository,
        ProductImage $productImageHelper,
        BookingProductRepository $bookingProductRepository,
        BookingHelper $bookingHelper
    )
    {
        parent::__construct(
            $attributeRepository,
            $productRepository,
            $attributeValueRepository,
            $productImageRepository,
            $productImageHelper
        );

        $this->bookingProductRepository = $bookingProductRepository;

        $this->bookingHelper = $bookingHelper;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return Product
     */
    public function update(array $data, $id, $attribute = "id"): Product
    {
        $product = parent::update($data, $id, $attribute);

        if (request()->route()->getName() != 'admin.catalog.products.massupdate') {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $id);

            if ($bookingProduct) {
                $this->bookingProductRepository->update(request('booking'), $bookingProduct->id);
            } else {
                $this->bookingProductRepository->create(array_merge(request('booking'), [
                    'product_id' => $id,
                ]));
            }
        }

        return $product;
    }

    /**
     * Returns additional views
     *
     * @param $productId
     * @return object
     */
    public function getBookingProduct($productId): object
    {

        static $bookingProducts = [];

        if (isset($bookingProducts[$productId])) {
            return $bookingProducts[$productId];
        }

         $bookingProducts[$productId] = $this->bookingProductRepository->findOneByField('product_id', $productId);

        return $bookingProducts[$productId];

    }

    /**
     * Return true if this product can have inventory
     *
     * @return bool
     */
    public function showQuantityBox(): bool
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        if (!$bookingProduct) {
            return false;
        }

        if (in_array($bookingProduct->type, ['default', 'rental', 'table'])) {
            return true;
        }

        return false;
    }

    /**
     * @param $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem, $sellerId = 0): bool
    {
        $bookingProduct = $this->getBookingProduct($this->product->id);

        return app($this->bookingHelper->getTypeHepler($bookingProduct->type))->isItemHaveQuantity($cartItem, $sellerId);
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param $data
     * @param $sellerId
     * @return string
     */
    public function prepareForCart($data, $sellerId = 0)
    {

        if (!isset($data['type']) || !isset($data['booking_product_id'])) {
            return trans('shop::app.checkout.cart.integrity.missing_options');
        }

        $products = [];


        $bookingProduct = $this->getBookingProduct($data['product_id']);

        if ($bookingProduct->type == 'event') {  /*|| $bookingProduct->type == 'training' || $bookingProduct->type == 'rental' || $bookingProduct->type == 'default'*/
             $filtered = Arr::where($data['selectedTickets'], function ($qty, $key) {
                 return $qty != 0;
             });



            if (!count($filtered)) {
                return trans('shop::app.checkout.cart.integrity.missing_options');
            }

            $i=0;

            foreach ($data['selectedTickets'] as $selectedTicket) {

                    if (!$selectedTicket['qty']) continue;
                    $preparedData=[
                        'product_id' => $data['product_id'],
                        'quantity' => $selectedTicket['qty'],
                        'booking' => ['ticket_id' =>$selectedTicket['ticketId']] ,/* ($bookingProduct->type == 'event') ? $selectedTicket['ticketId'] : $selectedTicket['slotId']] */
                    ];
                    if(isset($data['selectedSlot'])) $preparedData['selectedSlot']=$data['selectedSlot'];
                    if(isset($data['defaultSlots'])) $preparedData['defaultSlots']=$data['defaultSlots'];
                    if(isset($data['type'])) $preparedData['type']=$data['type'];
                    $cartProducts = parent::prepareForCart($preparedData);

                    if (is_string($cartProducts)) {
                        return $cartProducts;
                    }

                    $products = array_merge($products, $cartProducts);
                    $i += 1;

            }

        } else {
            $products = parent::prepareForCart($data);
        }

        $typeHelper = app($this->bookingHelper->getTypeHepler($bookingProduct->type));

        if (!$typeHelper->isSlotAvailable($products)) {

            if($bookingProduct->type=='event'){

                $remaining_tickets=$typeHelper->getRemainingTicketsNumber($cartProducts[0]);
                if($remaining_tickets < 0) $remaining_tickets=0;

              return 'The requested quantity is not available, Only '.$remaining_tickets.' tickets of this type remaining.';
            }
            return trans('shop::app.checkout.cart.quantity.inventory_warning');
        }


        if($bookingProduct->type=='event'){
            // If Maximum tickets per booking is set one user can only buy this many tickets, they can not do mulitple order either if they have already reached the max on a previous order
           /* if ($typeHelper->isBookingExceedMaximumTicketsPerBooking($products,$data['selectedTickets'][0]['qty'])) {
                return 'You cant Exceed Maximum tickets per booking ';
            }*/
            // Maximum event size is set but Number of tickets available is empty then tickets can be purchase in any amounts for the different tickets types as long as the combined total of all tickets sold does not exced the Maximum event size
            if ($typeHelper->isBookingExceedMaximumEventSize($products,$data['selectedTickets'][0]['qty'])) {
                return 'You cant Exceed Maximum Event Size ';
            }
        }


        $products = $typeHelper->addAdditionalPrices($products);

        return $products;
    }

    public function isValidBookingProductCartItem($item){

        $bookingProduct = $this->getBookingProduct($item->product_id);
        if($bookingProduct->type=='event'){
            $typeHelper = app($this->bookingHelper->getTypeHepler($bookingProduct->type));

/*            if ($typeHelper->isCartItemExceedNumberOfAvailableTickets($item,$bookingProduct)) {
                return 'You cant Exceed Number Of Available Tickets ';
            }*/

            if ($typeHelper->isCartItemExceedMaximumTicketsPerBooking($item,$bookingProduct)) {
                return false;
            }
            // Maximum event size is set but Number of tickets available is empty then tickets can be purchase in any amounts for the different tickets types as long as the combined total of all tickets sold does not exced the Maximum event size
            if ($typeHelper->isCartItemExceedMaximumEventSize($item,$bookingProduct)) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * @param $options1
     * @param $options2
     * @return boolean
     */
    public function compareOptions($options1, $options2): bool
    {
        if ($options1['product_id'] != $options2['product_id']) {
            return false;
        }

        if (isset($options1['booking']) && isset($options2['booking'])) {
            return $options1['booking']['ticket_id'] == $options2['booking']['ticket_id'];
        }

        if (isset($options1['rentalType']) && isset($options2['rentalType'])) {

            if ($options1['rentalType'] === 'daily') {
                $op1FromTime = Carbon::createFromTimeString($options1['selectedDateFrom'] . " 00:00:00")->getTimestamp();
                $op1ToTime = Carbon::createFromTimeString($options1['selectedDateTo'] . " 23:59:59")->getTimestamp();
            } else {
                $op1FromTime = Carbon::createFromTimestamp($options1['selectedSlotFrom'])->getTimestamp()+1;
                $op1ToTime = Carbon::createFromTimestamp($options1['selectedSlotTo'])->getTimestamp();
            }

            if ($options2['rentalType'] === 'daily') {
                $op2FromTime = Carbon::createFromTimeString($options2['selectedDateFrom'] . " 00:00:00")->getTimestamp();
                $op2ToTime = Carbon::createFromTimeString($options2['selectedDateTo'] . " 23:59:59")->getTimestamp();
            } else {
                $op2FromTime = Carbon::createFromTimestamp($options2['selectedSlotFrom'])->getTimestamp()+1;
                $op2ToTime = Carbon::createFromTimestamp($options2['selectedSlotTo'])->getTimestamp();
            }

            if ($op2FromTime >= $op1FromTime && $op2FromTime <= $op1ToTime) return true;
            if ($op2ToTime >= $op1FromTime && $op2ToTime <= $op1ToTime) return true;
            return false;
        }

        if (isset($options1['selectedSlot']) && isset($options2['selectedSlot'])) {
            if ($options1['selectedSlot'] != $options2['selectedSlot']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns additional information for items
     *
     * @param $data
     * @return array
     */
    public function getAdditionalOptions($data): array
    {
        return $this->bookingHelper->getCartItemOptions($data);
    }

    /**
     * Validate cart item product price
     *
     * @param $item
     * @param int $sellerId
     * @return float
     */
    public function validateCartItem($item, $sellerId = 0)
    {
        $bookingProduct = $this->getBookingProduct($item->product_id);

        if (!$bookingProduct) {
            return;
        }

        return app($this->bookingHelper->getTypeHepler($bookingProduct->type))->validateCartItem($item);
    }
}