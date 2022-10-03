<?php

namespace Webkul\BookingProduct\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository;
use Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository;
use Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository;
use Webkul\BookingProduct\Repositories\BookingRepository;

class Booking
{
    /**
     * BookingProductRepository
     *
     * @return \Webkul\BookingProduct\Repositories\BookingProductRepository
     */
    protected $bookingProductRepository;

    /**
     * @return array
     */
    protected $typeRepositories = [];

    /**
     * BookingRepository
     *
     * @return \Webkul\BookingProduct\Repositories\BookingRepository
     */
    protected $bookingRepository;

    /**
     * @return array
     */
    protected $typeHelpers = [
        'default'     => DefaultSlot::class,
        'appointment' => AppointmentSlot::class,
        'event'       => EventTicket::class,
        'rental'      => RentalSlot::class,
        'table'       => TableSlot::class,
    ];

    /**
     * @return array
     */
    protected $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\BookingProduct\Repositories\BookingProductRepository  $bookingProductRepository
     * @param  \Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository  $bookingProductDefaultSlotRepository

     * @param  \Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository  $bookingProductEventTicketRepository
     * @param  \Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository  $bookingProductRentalSlotRepository

     * @param  \Webkul\BookingProduct\Repositories\BookingRepository  $bookingRepository
     * @return void
     */
    public function __construct(
        BookingProductRepository $bookingProductRepository,
        BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        BookingProductEventTicketRepository $bookingProductEventTicketRepository,
        BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        BookingRepository $bookingRepository
    )
    {
        $this->bookingProductRepository = $bookingProductRepository;

        $this->typeRepositories['default'] = $bookingProductDefaultSlotRepository;


        $this->typeRepositories['event'] = $bookingProductEventTicketRepository;

        $this->typeRepositories['rental'] = $bookingProductRentalSlotRepository;


        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Returns the booking type hepler instance
     *
     * @param  string  $type
     * @return array
     */
    public function getTypeHepler($type)
    {
        if(isset($this->typeHelpers[$type])){
            return $this->typeHelpers[$type];
        }
        return $this->typeHelpers['default'];
    }

    /**
     * Returns the booking information
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return array
     */
    public function getWeekSlotDurations($bookingProduct)
    {
        $slotsByDays = [];

        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        $availabileDays = $this->getAvailableWeekDays($bookingProduct);

        foreach ($this->daysOfWeek as $index => $isOpen) {
            $slots = [];

            if ($isOpen) {
                $slots = $bookingProductSlot->same_slot_all_days ? $bookingProductSlot->slots : ($bookingProductSlot->slots[$index] ?? []);
            }

            $slotsByDays[] = [
                'name'  => trans($this->daysOfWeek[$index]),
                'slots' => isset($availabileDays[$index]) ? $this->conver24To12Hours($slots) : [],
            ];
        }

        return $slotsByDays;
    }

    /**
     * Returns html of slots for a current day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return string
     */
    public function getTodaySlotsHtml($bookingProduct)
    {
        $slots = [];

        foreach ($this->getTodaySlots($bookingProduct) as $slot) {
            $slots[] = $slot['from'] . ' - ' . $slot['to'];
        }

        return count($slots)
               ? implode(' | ', $slots)
               : '<span class="text-danger">' . trans('bookingproduct::app.shop.products.closed') . '</span>';
    }

    /**
     * Returns slots for a current day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return array
     */
    public function getTodaySlots($bookingProduct)
    {
        $weekSlots = $this->getWeekSlotDurations($bookingProduct);

        return $weekSlots[Carbon::now()->format('w')]['slots'];
    }

    /**
     * Returns the available week days
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return array
     */
    public function getAvailableWeekDays($bookingProduct)
    {
        if ($bookingProduct->available_every_week) {
            return $this->daysOfWeek;
        }

        $days = [];

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProduct->available_from && $bookingProduct->available_from
                         ? Carbon::createFromTimeString($bookingProduct->available_from)
                         : Carbon::createFromTimeString($currentTime->format('Y-m-d 00:00:00'));

        $availableTo = ! $bookingProduct->available_from && $bookingProduct->available_to
                       ? Carbon::createFromTimeString($bookingProduct->available_to)
                       : Carbon::createFromTimeString('2080-01-01 00:00:00');

        for ($i = 0; $i < 7; $i++) {
            $date = clone $currentTime;
            $date->addDays($i);

            if ($date >= $availableFrom && $date <= $availableTo) {
                $days[$i] = $date->format('l');
            }
        }

        return $this->sortDaysOfWeek($days);
    }

    /**
     * Sort days
     *
     * @param  array  $days
     * @return array
     */
    public function sortDaysOfWeek($days)
    {
        $daysAux = [];

        foreach ($days as $day) {
            $key = array_search($day, $this->daysOfWeek);

            if ($key !== FALSE) {
                $daysAux[$key] = $day;
            }
        }

        ksort($daysAux);

        return $daysAux;
    }

    /**
     * Convert time from 24 to 12 hour format
     *
     * @param  array  $slots
     * @return array
     */
    public function conver24To12Hours($slots)
    {
        if (! $slots) {
            return [];
        }

        foreach ($slots as $index => $slot) {
            $slots[$index]['from']  = Carbon::createFromTimeString($slot['from'])->format("h:i a");
            $slots[$index]['to']  = Carbon::createFromTimeString($slot['to'])->format("h:i a");
        }

        return $slots;
    }

    /**
     * Returns slots for a perticular day
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @param  string  $date
     * @return array
     */
    public function getSlotsByDate($bookingProduct, $date)
    {
        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        if (! is_array($bookingProductSlot->slots) || ! count($bookingProductSlot->slots)) {
            return [];
        }

        $currentTime = Carbon::now();

        $requestedDate = Carbon::createFromTimeString($date . " 00:00:00");

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                         ? Carbon::createFromTimeString($bookingProduct->available_from)
                         : Carbon::createFromTimeString($currentTime->format('Y-m-d 00:00:00'));

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                       ? Carbon::createFromTimeString($bookingProduct->available_to)
                       : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
                         ? $bookingProductSlot->slots
                         : ($bookingProductSlot->slots[$requestedDate->format('w')] ?? []);

        if ($requestedDate < $availableFrom
            || $requestedDate > $availableTo
        ) {
            return [];
        }

        $slots = [];

        foreach ($timeDurations as $timeDuration) {
            $fromChunks = explode(':', $timeDuration['from']);
            $toChunks = explode(':', $timeDuration['to']);

            $startDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $startDayTime->addMinutes(($fromChunks[0] * 60) + $fromChunks[1]);
            $tempStartDayTime = clone $startDayTime;

            $endDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $endDayTime->addMinutes(($toChunks[0] * 60) + $toChunks[1]);

            $isFirstIteration = true;

            while (1) {
                $from = clone $tempStartDayTime;
                $tempStartDayTime->addMinutes($bookingProductSlot->duration);

                if ($isFirstIteration) {
                    $isFirstIteration = false;
                } else {
                    $from->modify('+' . $bookingProductSlot->break_time . ' minutes');
                    $tempStartDayTime->modify('+' . $bookingProductSlot->break_time . ' minutes');
                }

                $to = clone $tempStartDayTime;

                if (($startDayTime <= $from && $from <= $availableTo)
                    && ($availableTo >= $to && $to >= $startDayTime)
                    && ($startDayTime <= $from && $from <= $endDayTime)
                    && ($endDayTime >= $to && $to >= $startDayTime)
                ) {
                    // Get already ordered qty for this slot
                    $orderedQty = 0;

                    $qty = isset($timeDuration['qty']) ? ( $timeDuration['qty'] - $orderedQty ) : 1;

                    if ($qty && $currentTime <= $from) {
                        $slots[] = [
                            'from'      => $from->format('h:i A'),
                            'to'        => $to->format('h:i A'),
                            'timestamp' => $from->getTimestamp() . '-' . $to->getTimestamp(),
                            'qty'       => $qty,
                        ];
                    }
                } else {
                    break;
                }
            }
        }

        return $slots;
    }

    /**
     * @param  \Webkul\Ceckout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);
        if($bookingProduct->type=='rental'){
            return true;
        }
        if ($bookingProduct->qty - $this->getBookedQuantity($cartItem) < $cartItem['quantity'] || $this->isSlotExpired($cartItem)) {
            return false;
        }

        return true;
    }

    /**
     * @param  array  $cartProducts
     * @return bool
     */
    public function isSlotAvailable($cartProducts)
    {
        foreach ($cartProducts as $cartProduct) {
            if (!$this->isItemHaveQuantity($cartProduct)) {
                return false;
            }
        }

        return true;
    }


    public function isBookingExceedMaximumTicketsPerBooking($cartProducts,$selectedQuantity)
    {
        foreach ($cartProducts as $cartProduct) {
            if ($this->isItemExceedMaximumTicketsPerBooking($cartProduct,$selectedQuantity)) {
                return true;
            }
        }
        return false;
    }

    public function isBookingExceedMaximumEventSize($cartProducts,$selected_ticket_qty)
    {

        $cart_items_quantity=app('Webkul\Checkout\Repositories\CartRepository')->getModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->addSelect(DB::raw('SUM(cart_items.quantity) as cart_items_quantity'))
            ->where('cart_items.product_id', $cartProducts[0]['product_id'])
            ->where('cart.is_active', 1)
            ->first();
        $nb_of_tickets_added_to_cart= ! is_null($cart_items_quantity->cart_items_quantity) ? $cart_items_quantity->cart_items_quantity : 0;

        foreach ($cartProducts as $cartProduct) {
            if ($this->isItemExceedMaximumEventSize($cartProduct,$nb_of_tickets_added_to_cart,$selected_ticket_qty)) {
                return true;
            }
        }

        return false;
    }

    public function isCartItemExceedNumberOfAvailableTickets($item,$bookingProduct,$ticket = null){
        return $this->checkCartItemExceedNumberOfAvailableTickets($item,$bookingProduct,$ticket);
    }

    public function isCartItemExceedMaximumTicketsPerBooking($item,$bookingProduct,$ticket = null){
        return $this->checkCartItemExceedMaximumTicketsPerBooking($item,$bookingProduct,$ticket);
    }

    public function isCartItemExceedMaximumEventSize($item,$bookingProduct ,$ticket = null){
        return $this->checkCartItemExceedMaximumEventSize($item,$bookingProduct,$ticket);
    }

    /**
     * @param  \Webkul\Ceckout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isSlotExpired($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        if (isset($this->typeHelpers[$bookingProduct->type])) {
            $typeHelper = app($this->typeHelpers[$bookingProduct->type]);
        } else {
            $typeHelper = app($this->typeHelpers['default']);
        }

        $slots = [];

        foreach ($cartItem['additional']['defaultSlots']['slots'] as $defaultSlots){
            foreach ($defaultSlots['durations'] as $defaultSlot){
                array_push($slots,$defaultSlot);
            }
        }


        $filtered = Arr::where($slots, function ($slot, $key) use ($cartItem) {
            if(!isset($cartItem['additional']['selectedSlot']['slotId']) && !isset($cartItem['additional']['selectedSlot']['durations']) && sizeof($cartItem['additional']['selectedSlot']) > 1){
                return $slot['slotId'] == $cartItem['additional']['selectedSlot'][sizeof($cartItem['additional']['selectedSlot']) - 1]['durations'][0]['slotId'];
            }
            if(isset($cartItem['additional']['selectedSlot']['slotId']))   return $slot['slotId'] == $cartItem['additional']['selectedSlot']['slotId'];
            if(isset($cartItem['additional']['selectedSlot']['durations']))   return $slot['slotId'] == $cartItem['additional']['selectedSlot']['durations'][0]['slotId'];

        });

        return count($filtered) ? false : true;
    }

    /**
     * @param  array  $data
     * @return int
     */
    public function getBookedQuantity($data)
    {

        if(isset($data['additional']['selectedSlot']['from'])) $timestamps = $data['additional']['selectedSlot']['from'];
        if(isset($data['additional']['selectedSlot']['durations'])) $timestamps = $data['additional']['selectedSlot']['durations'][0]['from'];
        $result = $this->bookingRepository->getModel()->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))->where('bookings.product_id', $data['product_id']);
        if (isset($timestamps)) {
            $result = $result->where('bookings.from', $timestamps[0])->where('bookings.to', $timestamps[1]);
        }
        $result = $result->first();

        return ! is_null($result->total_qty_booked) ? $result->total_qty_booked : 0;
    }

    /**
     * Returns additional cart item information
     *
     * @param $data
     * @return array
     */
    public function getCartItemOptions($data): array
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $data['product_id']);

        if (!$bookingProduct) {
            return $data;
        }

        switch ($bookingProduct->type) {
            case 'event':

             // $ticket = $bookingProduct->event_tickets()->find($data['booking']['ticket_id']);
                $tickets=json_decode($bookingProduct->event_tickets()->first()->tickets);

                $ticket='';
                foreach ($tickets as $item){
                    if($item->ticketId==$data['booking']['ticket_id']){
                      $ticket=$item;
                    }
                }

                $data['attributes'] = [
                    [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.event-ticket'),
                        'option_id'      => 0,
                        'option_label'   => $ticket->productTicketName,
                    ], [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.event-from'),
                        'option_id'      => 0,
                        'option_label'   => '',
                    ], [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.event-till'),
                        'option_id'      => 0,
                        'option_label'   => '',
                    ]
                ];

                break;

            case 'rental':

                unset($data['_token']);
                unset($data['quantity']);
                $rentingType = $data['rentalType'] ?? $bookingProduct->rental_slot->renting_type;

                if ($rentingType == 'daily') {
                    $from = Carbon::createFromTimeString($data['selectedSlot']['from'].' 00:00:00');
                    $to = Carbon::createFromTimestamp(strtotime("+".$data['selectedSlot']['nbOfDays']." day", strtotime($data['selectedSlot']['from'])));
                   // $to = Carbon::createFromTimeString($data['selectedSlot']['to'])->format('d F, Y');
                }elseif($rentingType == 'hourly') {
                    $from = Carbon::createFromTimestamp($data['selectedSlot']['from'])->format('d F, Y h:i A');

                    $to = Carbon::createFromTimestamp($data['selectedSlot']['to'])->format('d F, Y h:i A');
                }else{
                    $from = Carbon::createFromTimestamp($data['selectedSlot']['from'])->format('d F, Y h:i A');

                    $to = Carbon::createFromTimestamp($data['selectedSlot']['to'])->format('d F, Y h:i A');
                }

                $data['attributes'] = [
                    [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.rent-type'),
                        'option_id'      => 0,
                        'option_label'   => trans('bookingproduct::app.shop.cart.' . $rentingType),
                    ], [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.rent-from'),
                        'option_id'      => 0,
                        'option_label'   => $from,
                    ], [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.rent-till'),
                        'option_id'      => 0,
                        'option_label'   => $to,
                    ]
                ];

                break;
            default:
                if(isset($data['selectedSlot'])){
                        if(isset($data['selectedSlot']['durations'])){
                            $from =  $data['selectedSlot']['durations'][0]['from'];
                        }elseif(isset($data['selectedSlot']['from'])){
                            $from =  $data['selectedSlot']['from'];
                        }else{
                            $from=[];
                            foreach ($data['selectedSlot'] as $key => $selectedSlot) {
                                $from[$key]=$selectedSlot['durations'][0]['from'];
                            }
                            $fromTimeStamps=[];
                            if(sizeof($from) > 0){
                                foreach ($from as $key => $date) {
                                    $fromTimeStamps[$key]=Carbon::createFromTimestamp($date)->format('d F, Y h:i A');
                                }
                            }
                        }


                        if(isset($data['selectedSlot']['durations'])){
                            $to =  $data['selectedSlot']['durations'][0]['to'];
                        }elseif(isset($data['selectedSlot']['to'])){
                            $to =  $data['selectedSlot']['to'];
                        }else{
                            $to=[];
                            foreach ($data['selectedSlot'] as $key => $selectedSlot) {
                                $to[$key]=$selectedSlot['durations'][0]['to'];
                            }
                            $toTimeStamps=[];
                            if(sizeof($to) > 0){
                                foreach ($to as $key => $date) {
                                    $toTimeStamps[$key]=Carbon::createFromTimestamp($date)->format('d F, Y h:i A');
                                }
                            }
                        }

                }

                unset($data['_token']);
                unset($data['quantity']);
                $data['attributes'] = [
                    [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.booking-from'),
                        'option_id'      => 0,
                        'option_label'   => is_array($from) ? $fromTimeStamps : Carbon::createFromTimestamp($from)->format('d F, Y h:i A'),
                    ], [
                        'attribute_name' => trans('bookingproduct::app.shop.cart.booking-till'),
                        'option_id'      => 0,
                        'option_label'   => is_array($to) ? $toTimeStamps : Carbon::createFromTimestamp( $to)->format('d F, Y h:i A'),
                    ]
                ];

                break;
        }

        return $data;
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param  array  $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        return $products;
    }

    /**
     * Validate cart item product price
     *
     * @param $item
     * @return void
     */
    public function validateCartItem($item)
    {
        $price = $item->product->getTypeInstance()->getFinalPrice();

        if ($price == $item->base_price) {
            return;
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}