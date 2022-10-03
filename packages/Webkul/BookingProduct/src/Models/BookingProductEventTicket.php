<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductEventTicket as BookingProductEventTicketContract;

class BookingProductEventTicket extends Model implements BookingProductEventTicketContract
{
    public $timestamps = false;

    protected $fillable = [
        'maximum_event_size',
        'maximum_ticket_per_booking',
        'tickets',
        'booking_product_id',
    ];
}