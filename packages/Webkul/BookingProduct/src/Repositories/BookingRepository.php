<?php

namespace Webkul\BookingProduct\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;

class BookingRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\BookingProduct\Contracts\Booking';
    }

    /**
     * @param  array  $data
     * @return \Webkul\BookingProduct\Contracts\Booking
     */
    public function create(array $data)
    {
        $order = $data['order'];

        foreach ($order->items()->get() as $item) {
            if ($item->type != 'booking') {
                continue;
            }

         //   Event::dispatch('booking_product.booking.save.before', $item);

            $from = $to = null;

            if (isset($item->additional['selectedSlotFrom']) && isset($item->additional['selectedSlotTo'])) {
                $from = $item->additional['selectedSlotFrom'];
                $to = $item->additional['selectedSlotTo'];
            } elseif (isset($item->additional['selectedSlot'])) {
                //$timestamps =explode('-', $item->additional['selectedSlot']);
                if(isset($item->additional['selectedSlot']['from']) && isset($item->additional['selectedSlot']['to'])){
                    $from = $item->additional['selectedSlot']['from'];
                    $to = $item->additional['selectedSlot']['to'];
                }else{
                    $from='';
                    $to='';
                }
            } elseif (isset($item->additional['selectedDateFrom']) && isset($item->additional['selectedDateTo'])) {
                $from = Carbon::createFromTimeString($item->additional['selectedDateFrom'] . ' 00:00:00')->getTimestamp();
                $to = Carbon::createFromTimeString($item->additional['selectedDateTo'] . ' 23:59:59')->getTimestamp();
            }

            $booking = parent::create([
                'qty'                             => $item->qty_ordered,
                'from'                            => $from,
                'to'                              => $to,
                'order_id'                        => $order->id,
                'order_item_id'                   => $item->id,
                'product_id'                      => $item->product_id,
                'event_ticket_id'                 => $item->additional['booking']['ticket_id'] ?? null,
            ]);

           // Event::dispatch('marketplace.booking.save.after', $booking);
        }
    }
}