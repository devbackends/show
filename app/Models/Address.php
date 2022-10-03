<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'address1', 'address2', 'country', 'state', 'city', 'zip_code', 'phone'
    ];

}
