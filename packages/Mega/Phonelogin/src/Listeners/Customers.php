<?php

namespace Mega\Phonelogin\Listeners;

use Illuminate\Support\Facades\Log;
use Mega\Phonelogin\Exception\WrongVerificationCodeException;
use Mega\Phonelogin\Helper\PhoneloginHelper;
use Mega\Phonelogin\Repositories\OtpLogRepository;

class Customers
{
    protected $megaHelper;
    protected $otpRepo;
    protected $hasError;
    public function __construct(
        PhoneloginHelper $megaHelper,
        OtpLogRepository $otpLogRepository
    )
    {
        $this->megaHelper = $megaHelper;
        $this->otpRepo = $otpLogRepository;
        $this->hasError = false;
    }


    public function beforeRegistration(){

        if(!$this->megaHelper->isEnabled())
            return $this;
        if(!$this->megaHelper->phoneIsUnique(request()->input('phone'))){
            request()->flashExcept('password');
            session()->flash('info', "This Mobile Number is already associated with an account");
        }
        $phone = request()->input('phone',false);
        $otp = request()->input('megavcode',false);
        $row = $this->otpRepo->findByField('phone_number',$phone)->last();
        if(!$row){
            $this->hasError = true;
        }
        else if($row->verification_code == $otp){
            request()->merge(['mega_phone_verified' => 1]);
            return $this;
        }else{
            $this->hasError = true;
        }
        if($this->hasError){
            request()->flashExcept('password');
            session()->flash('info', __("Please verify your mobile number"));
            throw new WrongVerificationCodeException();
        }else{
            request()->merge(['mega_phone_verified' => 1]);
        }
        return $this;
    }


    public function afterRegistration($customer){

        if(!$this->megaHelper->isEnabled())
            return $this;
        //$customer = $event->customer;
        $customer->mega_phone_verified = 1;

    }
}