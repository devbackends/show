<?php

namespace Devvly\ClearentPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\ClearentPayment\Contracts\ClearentCart as Contract;

class ClearentCart extends Model implements Contract
{
    protected $table = 'clearent_cart';

    protected $fillable = [
        'cart_id', 'card_id'
    ];

    public function card()
    {
      return $this->belongsTo(ClearentCard::class,'card_id');
    }
}