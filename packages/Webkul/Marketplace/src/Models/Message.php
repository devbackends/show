<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Message as Contract;

class Message extends Model implements Contract
{
    public $timestamps = false;


    protected $table = 'marketplace_messages';

    protected $fillable = ['subject', 'read', 'spam', 'trash', 'archive', 'from', 'to'];

    protected $with = array('messageDetails');

    public function messageDetails()
    {
        return $this->hasMany(MessageDetail::class, 'message_id')->orderBy('id','asc');
    }


    protected $appends = array('customer_image','customer_name','shop_url');


    public function getCustomerImageAttribute()
    {
        if(auth()->guard('customer')->user()){
            $customer= auth()->guard('customer')->user();
            if($customer->id==$this->getFrom()){
                return  app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getTo())->image;
            }else{
                return  app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getFrom())->image;
            }
        }
       return '';

    }
    public function getCustomerNameAttribute()
    {
        if(auth()->guard('customer')->user()){
            $customer=$customer = auth()->guard('customer')->user();;
            if($customer->id==$this->getFrom()){
                $info= app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getTo());
                return $info->first_name.' '.$info->last_name;
            }else{
                $info= app('Webkul\Customer\Repositories\CustomerRepository')->find($this->getFrom());
                return $info->first_name.' '.$info->last_name;
            }
        }
return '';

    }
    public function getShopUrlAttribute()
    {
        if(auth()->guard('customer')->user()){
            $customer=$customer = auth()->guard('customer')->user();;
            if($customer->id==$this->getFrom()) {

                $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findOneWhere([
                    'customer_id' => $this->getTo()
                ]);
                if($seller){
                    return $seller->url;
                }
                return '';
            }else{
                $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findOneWhere([
                    'customer_id' => $this->getFrom()
                ]);
                if($seller){
                    return $seller->url;
                }
                return '';
            }
        }
     return '';
    }
    public function getFrom(){
        return $this->from;
    }
    public function getTo(){
        return $this->to;
    }
}