<?php

namespace Webkul\BookingProduct\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\Checkout\Facades\Cart;

class RentalSlot extends Booking
{
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

        $requestedDate = Carbon::createFromTimeString($date . " 00:00:00");

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                         ? Carbon::createFromTimeString($bookingProduct->available_from)
                         : Carbon::createFromTimeString($currentTime->format('Y-m-d 00:00:00'));

        $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                       ? Carbon::createFromTimeString($bookingProduct->available_to)
                       : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
                         ? $bookingProductSlot->slots
                         : $bookingProductSlot->slots[$requestedDate->format('w')] ?? [];

        if ($requestedDate < $availableFrom
            || $requestedDate > $availableTo
        ) {
            return [];
        }

        $slots = [];

        foreach ($timeDurations as $index => $timeDuration) {
            $fromChunks = explode(':', $timeDuration['from']);
            $toChunks = explode(':', $timeDuration['to']);

            $startDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $startDayTime->addMinutes(($fromChunks[0] * 60) + $fromChunks[1]);
            $tempStartDayTime = clone $startDayTime;

            $endDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $endDayTime->addMinutes(($toChunks[0] * 60) + $toChunks[1]);

            while (1) {
                $from = clone $tempStartDayTime;
                $tempStartDayTime->addMinutes(60);

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
                        if (! isset($slots[$index])) {
                            $slots[$index]['time'] = $startDayTime->format('h:i A') . ' - ' . $endDayTime->format('h:i A');
                        }

                        $slots[$index]['slots'][] = [
                            'from'           => $from->format('h:i A'),
                            'to'             => $to->format('h:i A'),
                            'from_timestamp' => $from->getTimestamp(),
                            'to_timestamp'   => $to->getTimestamp(),
                            'qty'            => $qty,
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
     * @param  array  $data
     * @return int
     */
    public function getBookedQuantity($data)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $data['product_id']);

        $rentingType = $data['additional']['rentalType'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::createFromTimeString($data['additional']['selectedSlot']['from'])->getTimestamp();

            $to = Carbon::createFromTimeString($data['additional']['selectedSlot']['to'])->getTimestamp();
        } elseif($rentingType == 'hourly') {
            $from = Carbon::createFromTimestamp($data['additional']['selectedSlot']['from'])->getTimestamp();

            $to = Carbon::createFromTimestamp($data['additional']['selectedSlot']['to'])->getTimestamp();
        }
        else {
            $from = Carbon::createFromTimestamp($data['additional']['selectedSlot']['from'])->getTimestamp();

            $to = Carbon::createFromTimestamp($data['additional']['selectedSlot']['to'])->getTimestamp();
        }

        $result = $this->bookingRepository->getModel()
                       ->leftJoin('order_items', 'bookings.order_item_id', '=', 'order_items.id')
                       ->addSelect(DB::raw('SUM(qty_ordered - qty_canceled - qty_refunded) as total_qty_booked'))
                       ->where('bookings.product_id', $data['product_id'])
                       ->where(function ($query) use($from, $to) {
                           $query->where(function ($query) use($from) {
                               $query->where('bookings.from', '<=', $from)->where('bookings.to', '>=', $from);
                           })
                           ->orWhere(function($query) use($to) {
                               $query->where('bookings.from', '<=', $to)->where('bookings.to', '>=', $to);
                           });
                       })
                       ->first();

        return ! is_null($result->total_qty_booked) ? $result->total_qty_booked : 0;
    }

    /**
     * @param  \Webkul\Ceckout\Contracts\CartItem|array  $cartItem
     * @return bool
     */
    public function isSlotExpired($cartItem)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $cartItem['product_id']);

        if (isset($cartItem['additional']['selectedDate'])) {
            $timeIntervals = $this->getSlotsByDate($bookingProduct, $cartItem['additional']['selectedDate']);

            $isExpired = true;

            foreach ($timeIntervals as $timeInterval) {
                $from = false;
                $to = false;
                foreach ($timeInterval['slots'] as $slot) {
                    if ($slot['from_timestamp'] == $cartItem['additional']['selectedSlotFrom']) {
                        $from = true;
                    }
                    if ($slot['to_timestamp'] == $cartItem['additional']['selectedSlotTo']) {
                        $to = true;
                    }
                }
                if ($from && $to) {
                    $isExpired = false;
                }
            }

            return $isExpired;
        } else {
            $currentTime = Carbon::now();
            $fromDate=explode(' ',$cartItem['additional']['selectedSlot']['from'])[0];
            $toDate=explode(' ',$cartItem['additional']['selectedSlot']['to'])[0];
            $requestedFromDate = Carbon::createFromTimeString($fromDate . " 00:00:00");

            $requestedToDate = Carbon::createFromTimeString($toDate . " 23:59:59");

            $availableFrom = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                        ? Carbon::createFromTimeString($bookingProduct->available_from)
                        : Carbon::createFromTimeString($currentTime->format('Y-m-d 00:00:00'));

/*            $availableTo = ! $bookingProduct->available_every_week && $bookingProduct->available_from
                    ? Carbon::createFromTimeString($bookingProduct->available_to)
                    : Carbon::createFromTimeString('2080-01-01 00:00:00');*/

            if ($requestedFromDate < $availableFrom
                /*|| $requestedFromDate > $availableTo*/
                || $requestedToDate < $availableFrom
                /*|| $requestedToDate > $availableTo*/
            ) {
                return true;
            }

            return false;
        }
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param  array  $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $products[0]['product_id']);

        $rentingType = $products[0]['additional']['rentalType'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::parse($products[0]['additional']['selectedSlot']['from']);
            //$to = Carbon::parse($products[0]['additional']['selectedSlot']['to']);
            $to = Carbon::createFromTimestamp(strtotime("+".$products[0]['additional']['selectedSlot']['nbOfDays']." day", strtotime($products[0]['additional']['selectedSlot']['from'])));
            $price = $products[0]['additional']['rental_slot']['daily_price'] * $to->diffInDays($from);
        } elseif($rentingType == 'hourly') {
            $from = Carbon::parse(  $products[0]['additional']['selectedSlot']['from']);
            $to = Carbon::parse($products[0]['additional']['selectedSlot']['to']);
            $hourly_price=$products[0]['additional']['rental_slot']['hourly_price'];
            $diffInHours=$to->diffInHours($from);
            $price = $hourly_price * $diffInHours;
        }else {
            $from = Carbon::parse(  $products[0]['additional']['selectedSlot']['from']);
            $to = Carbon::parse($products[0]['additional']['selectedSlot']['to']);
            $hourly_price=$products[0]['additional']['rental_slot']['hourly_price'];
            $diffInHours=$to->diffInHours($from);
            $price = $hourly_price * $diffInHours;
        }

        $products[0]['price'] = core()->convertPrice($price);
        $products[0]['base_price'] = $price;
        $products[0]['total'] = (core()->convertPrice($price) * $products[0]['quantity']);
        $products[0]['base_total'] = ($price * $products[0]['quantity']);

        return $products;
    }

    /**
     * Validate cart item product price
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return void|null
     */
    public function validateCartItem($item)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $item->product_id);

        $rentingType = $item->additional['rentalType'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            if (! isset($item->additional['selectedSlot']['from'])
            ) {
                Cart::removeItem($item->id);

                return true;
            }

            $from = Carbon::parse($item->additional['selectedSlot']['from']);
            $to = Carbon::createFromTimestamp(strtotime("+".$item->additional['selectedSlot']['nbOfDays']." day", strtotime($item->additional['selectedSlot']['from'])));

            $price = $bookingProduct->rental_slot->daily_price * $to->diffInDays($from);
        } else {
            if (! isset($item->additional['selectedSlot']['from'])
                || ! isset($item->additional['selectedSlot']['to'])
            ) {
                Cart::removeItem($item->id);

                return true;
            }

            $from = Carbon::parse($item->additional['selectedSlot']['from']);
            $to = Carbon::parse($item->additional['selectedSlot']['to']);
            $rental_slot=$bookingProduct->rental_slot;
            $price = $rental_slot->hourly_price * $to->diffInHours($from);
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}