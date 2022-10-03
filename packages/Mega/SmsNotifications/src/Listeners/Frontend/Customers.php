<?php

namespace Mega\SmsNotifications\Listeners\Frontend;

use Illuminate\Support\Facades\Log;
use Mega\SmsNotifications\Helpers\Customers\Messages as MessagesHelper;
use Mega\SmsNotifications\Helpers\Admin\Message as AdminMessageHelper;

class Customers
{

    protected $messageHelper;

    protected $adminMessageHelper;

    public function __construct(
        MessagesHelper $messagesHelper,
        AdminMessageHelper $adminMessageHelper
    )
    {
        $this->adminMessageHelper = $adminMessageHelper;
        $this->messageHelper = $messagesHelper;
    }

    public function AfterLogin($email)
    {

        try{
            $user = auth()->guard('customer')->user();
            if(!isset($user)) return;
            $userPhone = $user->phone;
            if($userPhone == '') return;
            if(!$this->messageHelper->extensionEnabled()) return;
            if(!$this->messageHelper->sendNotificationOnCustomerLogin()) return;
            $message = $this->messageHelper->getCustomerLoginMessage($user);
            $this->messageHelper->sendMessage($userPhone,$message);
        }catch (\Exception $e){
            Log::debug('exception occured '.$e->getMessage());
            Log::debug('exception occured '.$e->getTraceAsString());

            throw new \Exception($e->getMessage());
        }

    }



    public function AfterRegistration($customer){
        $this->sendAfterRegistrationToCustomer($customer);
        $this->sendAfterRegistrationToAdmin($customer);
    }


    public function sendAfterRegistrationToCustomer($customer){
        if(!isset($customer)) return;
        $userPhone = $customer->phone;
        if($userPhone == '') return;
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendCustomerRegistrationNotifications()) return;
        $message = $this->messageHelper->getCustomerRegistrationMessage($customer);
        $this->messageHelper->sendMessage($userPhone,$message);
    }


    public function sendAfterRegistrationToAdmin($customer)
    {
        if(!isset($customer)){;return;}
        $userPhone = $customer->phone;
        if($userPhone == '') { return;}
        if(!$this->messageHelper->extensionEnabled()){ return;}
        if(!$this->messageHelper->sendNotificationOnCustomerRegistrationtoAdmin()) { 
            return;}
        $message = $this->messageHelper->getCustomerRegistrationMessageForAdmin($customer);
        $adminPhone = $this->messageHelper->getAdminPhone();
        $this->messageHelper->sendMessage($adminPhone,$message);
    }

    /**
     * @param $customer
     */
    public function AfterPasswordReset($customer){
        if(!isset($customer)) return
        $userPhone = $customer->phone;
        if($customer->phone == '') return;
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendNotificationsOnPasswordReset()) return;
        $message = $this->messageHelper->getCustomerResetPasswordMessage($customer);
        $this->messageHelper->sendMessage($customer->phone,$message);
    }


    /*
     * 
     * 
     * */
    public function AfterPlaceOrder($order){
        $this->sendPlaceOrderNotificationToAdmin($order);
        $this->sendPlaceOrderNotificationToCustomer($order);
    }

    public function sendPlaceOrderNotificationToAdmin($order){
        $adminPhone = $this->messageHelper->getAdminPhone();
        if(!$adminPhone){
            Log::debug('Admin Mobile Number Not configured');
            return;
        }
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendNewOrderNotificationAdmin()) return;
        $message = $this->adminMessageHelper->getNewOrderMessage($order);

        $this->messageHelper->sendMessage($adminPhone,$message);
    }

    public function sendPlaceOrderNotificationToCustomer($order){
        $customer = $order->customer;
        if(!$customer){
            Log::debug('No Customer for order '.$order->id);
            return;
        }
        $messageReceiver = $this->messageHelper->getMessageReciever();
        $address = $order->shipping_address;
        $userPhone = [];
        switch ($messageReceiver){
            case 0:
                $userPhone[] = $customer->phone;
                break;
            case 1:
                $userPhone[] = $address->phone;
                break;
            case 2:
                if($customer->phone && $customer->phone != '')
                    $userPhone[] = [$address->phone, $customer->phone];
                else
                    $userPhone[] = [$address->phone];
                break;
        }

        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendNewOrderNotificationCustomer()) return;
        $message = $this->messageHelper->getOrderMessageCustomer($customer, $order);
        foreach ($userPhone as $phone){
            if(!$phone)
                continue;
            $this->messageHelper->sendMessage($phone,$message);
        }

    }

}
