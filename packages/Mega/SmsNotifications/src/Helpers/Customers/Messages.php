<?php

namespace Mega\SmsNotifications\Helpers\Customers;

use Mega\SmsNotifications\Helpers\CommonHelper;
use Illuminate\Support\Facades\Log;
use Mega\SmsNotifications\Helpers\Admin\Message as AdminMessagesHelper;
use Mega\SmsNotifications\Models\MegaSmsLog;

class Messages extends CommonHelper
{
    protected $adminMessagesHelper;

    public function __construct(
        MegaSmsLog $smsLogModel,
        AdminMessagesHelper $adminMessagesHelper
    )
    {
        $this->adminMessagesHelper = $adminMessagesHelper;
        parent::__construct($smsLogModel);
    }

    public function getCustomerLoginMessage($user){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.customer.success_login_template');
        $find = ['__customer_firstname__','__customer_lastname__'];
        $rep = [$user->first_name,$user->last_name];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer login messaeg '.$user->id.' -- '.$message);
        return $message;
    }

    public function getCustomerRegistrationMessage($user){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.customer.success_registration_template');
        $find = ['__customer_firstname__','__customer_lastname__'];
        $rep = [$user->first_name,$user->last_name];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer registration message '.$user->id.' -- '.$message);
        return $message;
    }

    public function getCustomerRegistrationMessageForAdmin($user){
        return $this->adminMessagesHelper->getCustomerRegistrationMessage($user);
    }

    public function getCustomerResetPasswordMessage($user){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.customer.success_password_reset_template');
        $find = ['__customer_firstname__','__customer_lastname__'];
        $rep = [$user->first_name,$user->last_name];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer reset password '.$user->id.' -- '.$message);
        return $message;
    }

    public function getOrderMessageCustomer($customer,$order){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.customer.new_order_template');
        $find = ['__customer_firstname__','__customer_lastname__','__order_amount__'];
        $rep = [$customer->first_name,$customer->last_name,$order->grand_total];
        $message = str_replace($find,$rep,$messagesTemplate);

        Log::debug('customer reset password '.$customer->id.' -- '.$message);
        return $message;
    }

}