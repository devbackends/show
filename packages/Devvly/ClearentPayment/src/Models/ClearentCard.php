<?php

namespace Devvly\ClearentPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\ClearentPayment\Contracts\ClearentCard as Contract;

class ClearentCard extends Model implements Contract
{
    protected $table = 'clearent_cards';

    protected $fillable = ['customers_id', 'jwt_token','card_type','card_token','last_four','exp_date', 'save', 'is_default'];
    protected $hidden = ['jwt_token', 'card_token'];
}