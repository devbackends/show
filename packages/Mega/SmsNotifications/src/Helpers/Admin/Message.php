<?php

namespace Mega\SmsNotifications\Helpers\Admin;

use Illuminate\Support\Facades\Log;
use Mega\SmsNotifications\Helpers\AbstractHelper;
use Mega\SmsNotifications\Helpers\CommonHelper;


class Message extends CommonHelper
{


    public function getCustomerRegistrationMessage($user){

        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.admin.customer_registration_template');
        $find = ['__customer_firstname__','__customer_lastname__','__user_email__'];
        $rep = [$user->first_name,$user->last_name,$user->email];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer registration message for admin '.$user->id.' -- '.$message);
        return $message;
    }


    public function getNewOrderMessage($order){

        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.admin.new_order_template');
        $user = $order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__order_amount__','__order_id__','__order_currency__'];
        $rep = [$user->first_name,$user->last_name,$user->email,$order->grand_total,$order->id,$order->order_currency_code];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer new order admin'.$user->id.' -- '.$message);
        return $message;
    }

    public function getInvoiceNotificationMessageAdmin($invoice){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.admin.new_invoice_template');
        $order = $invoice->order;
        $user = $invoice->order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__invoice_id__','__invoice_amount__','__order_currency__','__order_id__','__admin_name__'];
        $rep =  [$user->first_name,$user->last_name,$user->email,$invoice->id,$invoice->grand_total,$order->order_currency_code,$order->id,auth()->guard('admin')->user()->name];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer new invocce admin'.$user->id.' -- '.$message);
        return $message;
    }

    public function getInvoiceNotificationMessageCustomer($invoice){
        $messageTemplate = $this->getConfigData('megasmsnotifications.general.customer.new_invoice_template');
        $order = $invoice->order;
        $user = $invoice->order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__invoice_id__','__invoice_amount__','__order_currency__','__order_id__','__admin_name__'];
        $rep =  [$user->first_name,$user->last_name,$user->email,$invoice->id,$invoice->grand_total,$order->order_currency_code,$order->id,auth()->guard('admin')->user()->name];
        $message = str_replace($find,$rep,$messageTemplate);
        Log::debug('customer new invocce admin'.$user->id.' -- '.$message);
        return $message;
    }


    public function getCancelOrderMessage($order){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.admin.order_cancel_template');
        $user = $order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__order_amount__','__order_id__','__order_currency__','__admin_name__'];
        $rep = [$user->first_name,$user->last_name,$user->email,$order->grand_total,$order->id,$order->order_currency_code,auth()->guard('admin')->user()->name];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer new order admin'.$user->id.' -- '.$message);
        return $message;
    }

    public function getOrderCancelMessageCustomer($order){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.customer.order_cancel_template');
        $user = $order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__order_amount__','__order_id__','__order_currency__'];
        $rep = [$user->first_name,$user->last_name,$user->email,$order->grand_total,$order->id,$order->order_currency_code];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer order cancel '.$user->id.' -- '.$message);
        return $message;
    }

    public function getShipmentMessageCustomer($order,$shipment){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.customer.new_shipment_template');
        $user = $order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__order_amount__','__order_id__','__order_currency__','__shipment_id__'];
        $rep = [$user->first_name,$user->last_name,$user->email,$order->grand_total,$order->id,$order->order_currency_code,$shipment->id];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer shipment '.$user->id.' -- '.$message);
        return $message;
    }

    public function getShipmentMessageAdmin($order,$shipment){
        $messagesTemplate = $this->getConfigData('megasmsnotifications.general.admin.new_shipment_template');
        $user = $order->customer;
        $find = ['__customer_firstname__','__customer_lastname__','__customer_email__','__order_amount__','__order_id__','__order_currency__','__shipment_id__','__admin_name__'];
        $rep = [$user->first_name,$user->last_name,$user->email,$order->grand_total,$order->id,$order->order_currency_code,$shipment->id,auth()->guard('admin')->user()->name];
        $message = str_replace($find,$rep,$messagesTemplate);
        Log::debug('customer shipment '.$user->id.' -- '.$message);
        return $message;
    }
}