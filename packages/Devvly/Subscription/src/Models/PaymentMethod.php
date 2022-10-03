<?php

namespace Devvly\Subscription\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_method';
    protected $fillable = [
      'card_token',
      'jwt_token',
      'card_type',
      'last_four',
      'exp_date',
    ];
}