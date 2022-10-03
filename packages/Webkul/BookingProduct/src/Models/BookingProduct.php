<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\BookingProduct\Contracts\BookingProduct as BookingProductContract;

class BookingProduct extends Model implements BookingProductContract
{
    protected $fillable = [
        'name',
        'description',
        'qty',
        'type',
        'instructions',
        'leaders',
        'booking_confirmation_message',
        'tags',
        'min_age',
        'max_age',
        'age_restrictions',
        'gender',
        'levels',
        'location_type',
        'location',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'location_additional_information',
        'product_id'
    ];

    protected $with = ['default_slot', 'event_tickets', 'rental_slot'];

    protected $casts = [
        'available_from' => 'datetime',
        'available_to'   => 'datetime',
    ];

    /**
     * The Product Default Booking that belong to the product booking.
     */
    public function default_slot()
    {
        return $this->hasOne(BookingProductDefaultSlotProxy::modelClass());
    }


    /**
     * The Product Event Booking that belong to the product booking.
     */
    public function event_tickets()
    {
        return $this->hasOne(BookingProductEventTicketProxy::modelClass());
    }

    /**
     * The Product Rental Booking that belong to the product booking.
     */
    public function rental_slot()
    {
        return $this->hasOne(BookingProductRentalSlotProxy::modelClass());
    }

}