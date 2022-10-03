<?php

namespace Mega\Phonelogin\Helper;

use Webkul\Customer\Repositories\CustomerRepository;

class PhoneloginHelper
{

    protected $customerRepo;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function isEnabled(){
        return $this->getConfig('megaPhoneLogin.general.general.active');
    }


    public function phoneIsUnique($phone){
        $customers = $this->customerRepo->findByField('phone',$phone,['id']);
        foreach ($customers as $customer){
            if($customer->id)
                return false;
        }
        return true;
    }

    public function generateVerificationCode(){
        $code = mt_rand(100000, 999999);
        return $code;
    }

    public function findEmailByPhone($phone){
        $customer = $this->customerRepo->findByField('phone',$phone);
        if($customer->count() > 1 || $customer->count() == 0)
            return false;
        return $customer->first()->email;
    }

    public function getConfig($config){
        return core()->getConfigData($config);
    }

}