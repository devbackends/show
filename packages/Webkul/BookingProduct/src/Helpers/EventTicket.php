<?php

namespace Webkul\BookingProduct\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventTicket extends Booking
{
    /**
     * Returns event date
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return string
     */
    public function getEventDate($bookingProduct)
    {
        $from = Carbon::createFromTimeString($bookingProduct->available_from)->format('d F, Y h:i A');

        $to = Carbon::createFromTimeString($bookingProduct->available_to)->format('d F, Y h:i A');

        return $from . ' - ' . $to;
    }

    /**
     * Returns tickets
     *
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return array
     */
    public function getTickets($bookingProduct)
    {
        if (! $bookingProduct->event_tickets()->count()) {
            return;
        }

        return $this->formatPrice($bookingProduct->event_tickets);
    }

    /**
     * Format ticket price
     *
     * @param  array  $tickets
     * @return array
     */
    public function formatPrice($tickets)
    {

        foreach ($tickets as $index => $ticket) {
            $tickets[$index]['id'] = $ticket->id;
            $tickets[$index]['converted_price'] = core()->convertPrice($ticket->price);
            $tickets[$index]['formated_price'] = $formatedPrice = core()->currency($ticket->price);
            $tickets[$index]['formated_price_text'] = trans('bookingproduct::app.shop.products.per-ticket-price', ['price' => $formatedPrice]);
        }

        return $tickets;
    }

    public function getRemainingTicketsNumber($cartItem){
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id',$cartItem['product_id']);

        //get cart items
        $cart_items_quantity=app('Webkul\Checkout\Repositories\CartRepository')->getModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->addSelect(DB::raw('SUM(cart_items.quantity) as cart_items_quantity'))
            ->where('cart_items.product_id', $cartItem['product_id'])
            ->where('cart.is_active', 1)
            ->first();
        $nb_of_tickets_added_to_cart= ! is_null($cart_items_quantity->cart_items_quantity) ? $cart_items_quantity->cart_items_quantity : 0;

        $tickets=json_decode($bookingProduct->event_tickets()->first()->tickets);
        $ticket='';
        foreach ($tickets as $item){
            if($item->ticketId==$cartItem['additional']['booking']['ticket_id']){
                $ticket=$item;
            }
        }
        return $ticket->nbOfAvailableTickets - $this->getBookedQuantity($cartItem) - $nb_of_tickets_added_to_cart;
    }

    /**
     * @param \Webkul\Checkout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);
        $tickets=json_decode($bookingProduct->event_tickets()->first()->tickets);
        $ticket='';
        foreach ($tickets as $item){
            if($item->ticketId==$cartItem['additional']['booking']['ticket_id']){
                $ticket=$item;
            }
        }
        if(!$ticket->nbOfAvailableTickets){
            return true;
        }
        if ($ticket->nbOfAvailableTickets - $this->getBookedQuantity($cartItem) < $cartItem['quantity']) {
            return false;
        }

        return true;
    }


    public function isItemExceedMaximumTicketsPerBooking($cartItem,$selectedQuantity){

        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);
        $eventTicket=$bookingProduct->event_tickets()->first();

        //get cart items
        $cart_items_quantity=app('Webkul\Checkout\Repositories\CartRepository')->getModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->addSelect(DB::raw('SUM(cart_items.quantity) as cart_items_quantity'))
            ->where('cart_items.product_id', $cartItem['product_id'])
            ->where('cart.is_active', 1);
            if(auth()->guard('customer')->user()){
                $cart_items_quantity=$cart_items_quantity->where('cart.customer_id', auth()->guard('customer')->user()->id);
            }

        $cart_items_quantity=$cart_items_quantity->first();
        $nb_of_tickets_added_to_cart= ! is_null($cart_items_quantity->cart_items_quantity) ? $cart_items_quantity->cart_items_quantity : 0;
        if(!$eventTicket->maximum_ticket_per_booking){
            return false;
        }
        if(auth()->guard('customer')->user()){
            if ($eventTicket->maximum_ticket_per_booking - $this->getBookedQuantity($cartItem)   < $selectedQuantity +  $nb_of_tickets_added_to_cart ) {  /*$cartItem['quantity']*/
                return true;
            }
        }else{
            if ($eventTicket->maximum_ticket_per_booking - $this->getBookedQuantity($cartItem)   < $cartItem['quantity'] ) {  /*$cartItem['quantity']*/
                return true;
            }
        }


        return false;
    }

    public function isItemExceedMaximumEventSize($cartItem,$nb_of_tickets_addet_to_cart,$selected_ticket_qty){

        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);
        $eventTicket=$bookingProduct->event_tickets()->first();
        $tickets=json_decode($eventTicket->tickets);

        $ticket='';
        foreach ($tickets as $item){
            if($item->ticketId==$cartItem['additional']['booking']['ticket_id']){
                $ticket=$item;
            }
        }
        if(!$ticket->nbOfAvailableTickets){
            return false;
        }
        if($eventTicket->maximum_event_size ){
            /*if ($eventTicket->maximum_event_size - $this->getBookedEventTicketsQuantity($cartItem) - $nb_of_tickets_addet_to_cart  < $selected_ticket_qty) {*/
            if ($eventTicket->maximum_event_size - $this->getBookedEventTicketsQuantity($cartItem)  < $selected_ticket_qty) {
                return true;
            }
        }

        return false;
    }


    public function checkCartItemExceedNumberOfAvailableTickets($item,$bookingProduct,$ticket=null){

        if($item){
            $eventTicket=$bookingProduct->event_tickets()->first();
            $tickets=json_decode($eventTicket->tickets);
            $ticket='';
            foreach ($tickets as $ticketItem){
                if($ticketItem->ticketId==$item->additional['booking']['ticket_id']){
                    $ticket=$ticketItem;
                }
            }
            if(!$ticket->nbOfAvailableTickets){
                return false;
            }
            if ($ticket->nbOfAvailableTickets - $this->getBookedQuantity($item) < $item['quantity']) {

                return true;
            }

        }
        if($ticket){
            if(!$ticket->nbOfAvailableTickets){
                return false;
            }
            if ($ticket->nbOfAvailableTickets - $this->getBookedQuantity(null,$ticket) < 1) {

                return true;
            }
        }



        return false;
    }



    public function checkCartItemExceedMaximumTicketsPerBooking($item,$bookingProduct,$ticket=null){

        $eventTicket=$bookingProduct->event_tickets()->first();
        //get cart items
        $cart_items_quantity=app('Webkul\Checkout\Repositories\CartRepository')->getModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->addSelect(DB::raw('SUM(cart_items.quantity) as cart_items_quantity'))
            ->where('cart_items.product_id', $bookingProduct->product_id)
            ->where('cart.is_active', 1);
            if(auth()->guard('customer')->user()){
                $cart_items_quantity=$cart_items_quantity->where('cart.customer_id', auth()->guard('customer')->user()->id);
            }

        $cart_items_quantity=$cart_items_quantity->first();
        $nb_of_tickets_added_to_cart= ! is_null($cart_items_quantity->cart_items_quantity) ? $cart_items_quantity->cart_items_quantity : 0;

        if(!$eventTicket->maximum_ticket_per_booking){
            return false;
        }
        if($item){
            if ($eventTicket->maximum_ticket_per_booking - $this->getBookedQuantity($item)   <   $nb_of_tickets_added_to_cart ) {
                return true;
            }
        }
        if($ticket){
            if ($eventTicket->maximum_ticket_per_booking - $this->getBookedQuantity(null,$ticket)   <   $nb_of_tickets_added_to_cart ) {
                return true;
            }
        }


        return false;
    }

    public function checkCartItemExceedMaximumEventSize($item,$bookingProduct,$ticket=null){

        $cart_items_quantity=app('Webkul\Checkout\Repositories\CartRepository')->getModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->addSelect(DB::raw('SUM(cart_items.quantity) as cart_items_quantity'))
            ->where('cart_items.product_id',$bookingProduct->product_id)
            ->where('cart.is_active', 1)
            ->first();
        $nb_of_tickets_added_to_cart= ! is_null($cart_items_quantity->cart_items_quantity) ? $cart_items_quantity->cart_items_quantity : 0;
        $eventTicket=$bookingProduct->event_tickets()->first();

        if($item){
            $tickets=json_decode($eventTicket->tickets);
            $ticket='';
            foreach ($tickets as $ticketItem){
                if($ticketItem->ticketId==$item->additional['booking']['ticket_id']){
                    $ticket=$ticketItem;
                }
            }
            if(!$ticket->nbOfAvailableTickets){
                return false;
            }
            if($eventTicket->maximum_event_size ){
                if ($eventTicket->maximum_event_size - $this->getBookedEventTicketsQuantity($item)   < $nb_of_tickets_added_to_cart) {
                    return true;
                }
            }
        }
        if($ticket){
            if(!$ticket->nbOfAvailableTickets){
                return false;
            }
            if($eventTicket->maximum_event_size ){
                if ($eventTicket->maximum_event_size - $this->getBookedEventTicketsQuantity(null,$bookingProduct->product_id)   < $nb_of_tickets_added_to_cart) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * @param  array  $data
     * @return int
     */
    public function getBookedQuantity($data,$ticket=null)
    {
        if($data){
            $result = $this->bookingRepository->getModel()
                ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                ->where('bookings.product_id', $data['product_id'])
                ->where('bookings.event_ticket_id', $data['additional']['booking']['ticket_id'])
                ->first();
        }
        if($ticket){
            $result = $this->bookingRepository->getModel()
                ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                ->where('bookings.event_ticket_id', $ticket->ticketId)
                ->first();
        }


        return ! is_null($result->total_qty_booked) ? $result->total_qty_booked : 0;
    }

    public function getBookedEventTicketsQuantity($data,$product_id= null){

        if($data){
            $result = $this->bookingRepository->getModel()
                ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                ->where('bookings.product_id', $data['product_id'])
                ->first();
        }
        if($product_id){
            $result = $this->bookingRepository->getModel()
                ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                ->where('bookings.product_id', $product_id)
                ->first();

        }

        return ! is_null($result->total_qty_booked) ? $result->total_qty_booked : 0;
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param  array  $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        foreach ($products as $key => $product) {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $product['product_id']);

            $tickets=json_decode($bookingProduct->event_tickets()->first()->tickets);

            $ticket='';
            foreach ($tickets as $item){
                if($item->ticketId==$product['additional']['booking']['ticket_id']){
                    $ticket=$item;
                }
            }

            $products[$key]['price'] = core()->convertPrice($ticket->ticketPrice);
            $products[$key]['base_price'] = $ticket->ticketPrice;
            $products[$key]['total'] = (core()->convertPrice($ticket->ticketPrice) * $product['quantity']);
            $products[$key]['base_total'] = ($ticket->ticketPrice * $product['quantity']);
        }
        return $products;
    }

    /**
     * Validate cart item product price
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return float
     */
    public function validateCartItem($item)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $item->product_id);

        $tickets=json_decode($bookingProduct->event_tickets()->first()->tickets);
        $ticket='';

        foreach ($tickets as $obj){
            if($obj->ticketId==$item->additional['booking']['ticket_id']){
                $ticket=$obj;
            }
        }

        $price = $ticket->ticketPrice;

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