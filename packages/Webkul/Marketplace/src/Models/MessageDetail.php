<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\MessageDetail as Contracts;

class MessageDetail extends Model implements Contracts
{


    protected $table = 'marketplace_message_details';

    protected $fillable = ['body', 'read', 'message_id', 'from', 'to'];

    protected $appends = array('customer_image', 'customer_name', 'message_status');

    public function getCustomerImageAttribute()
    {
        if (auth()->guard('customer')->user()) {
            $customer = auth()->guard('customer')->user();
            if ($customer->id == $this->getFrom()) {
                return app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getTo())->image;
            } else {
                return app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getFrom())->image;
            }
        }
        return '';

    }

    public function getCustomerNameAttribute()
    {
        if (auth()->guard('customer')->user()) {
            $customer = $customer = auth()->guard('customer')->user();;
            if ($customer->id == $this->getFrom()) {
                $info = app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getTo());
                return $info->first_name . ' ' . $info->last_name;
            } else {
                $info = app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getFrom());
                return $info->first_name . ' ' . $info->last_name;
            }
        }
        return '';

    }

    public function getMessageStatusAttribute()
    {
        if (auth()->guard('customer')->user()) {
            $customer = $customer = auth()->guard('customer')->user();;
            if ($customer->id == $this->getFrom()) {
                return 'out';
            } else {
                return 'in';
            }
        }

    }

    public function getBody()
    {
        return $this->ucwords($this->body);
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

}