<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductDefaultSlot as BookingProductDefaultSlotContract;

class BookingProductDefaultSlot extends Model implements BookingProductDefaultSlotContract
{
    protected $table = 'booking_product_default_slots';

    public $timestamps = false;

    protected $casts = ['slots' => 'array'];

    protected $fillable = [
        'start_date',
        'end_date',
        'type_of_event',
        'repetition_type',
        'repetition_sequence',
        'repeat_until_type',
        'repeat_until_value',
        'repeat_until_number',
        'slots',
        'booking_product_id'
    ];

    /**
     * Get the product that owns the attribute value.
     */
    public function booking_product()
    {
        return $this->belongsTo(BookingProductProxy::modelClass());
    }
}