<?php

namespace Mega\SmsNotifications\Models;

use Illuminate\Database\Eloquent\Model;

class MegaSmsLog extends Model
{
    //protected $table = 'mega_sms_log';
    const API_NO_API = 0;
    const API_SPRING_EDGE = 1;
    const API_MSG91 = 2;
    const API_TEXTLOCAL = 3;
    const API_TWILIO = 4;
    const API_JAWALBSMS = 5;

    public $timestamps = false;

    protected $fillable = ['mobile_number','status','text'];

}
