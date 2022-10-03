<?php

namespace Mega\Phonelogin\Models;

use Illuminate\Database\Eloquent\Model;

class OtpLog extends Model
{
    protected $fillable = ['phone_number','verification_code'];
    const API_NO_API = 0;
    const API_SPRING_EDGE = 1;
    const API_MSG91 = 2;
    const API_TEXTLOCAL = 3;
    const API_TWILIO = 4;
    const API_JAWALBSMS = 5;

}
