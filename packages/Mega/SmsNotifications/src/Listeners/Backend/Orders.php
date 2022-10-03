<?php

namespace Mega\SmsNotifications\Listeners\Backend;

use Illuminate\Support\Facades\Log;
use Mega\SmsNotifications\Helpers\Admin\Message as AdminMessageHelper;

class Orders
{
    protected $messageHelper;

    public function __construct(
        AdminMessageHelper $messageHelper
    )
    {
        $this->messageHelper = $messageHelper;
    }


    public function AfterInvoice($invoice){
        $this->sendInvoiceNotificationToAdmin($invoice);
        $this->sendInvoiceNotificationToCustomer($invoice);
    }


    public function sendInvoiceNotificationToAdmin($invoice){
        $adminPhone = $this->messageHelper->getAdminPhone();
        if(!$adminPhone){
            Log::debug('Admin Mobile Number Not configured');
            return;
        }
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendInvoiceNotificationAdmin()) return;
        $message = $this->messageHelper->getInvoiceNotificationMessageAdmin($invoice);
        $this->messageHelper->sendMessage($adminPhone,$message);
    }

    public function sendInvoiceNotificationToCustomer($invoice){
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendInvoiceNotificationCustomer()) return;

        $customer = $invoice->order->customer;

        $order = $invoice->order;
        if(!$customer){
            Log::debug('No Customer for invoice of order '.$order->id);
            return;
        }
        $phones = $this->getRecieverPhone($order,$customer);
        $message = $this->messageHelper->getInvoiceNotificationMessageCustomer($invoice);
        foreach ($phones as $phone) {
            $this->messageHelper->sendMessage($phone,$message);
        }
    }

    public function getRecieverPhone($order,$customer){
        $messageReceiver = $this->messageHelper->getMessageReciever();
        $address = $order->shipping_address;
        $userPhone = [];
        switch ($messageReceiver){
            case 0:
                $userPhone[] = $customer->phone;
                break;
            case 1:
                $userPhone[] = [$address->phone];
                break;


            case 2:
                if($customer->phone && $customer->phone != '')
                    $userPhone[] = [$address->phone, $customer->phone];
                else
                    $userPhone[] = [$address->phone];
                break;
        }
        return $userPhone;
    }


    public function AfterCancel($order){
        $this->sendOrderNotificationToAdmin($order);
        $this->sendOrderNotificationToCustomer($order);
    }

    public function sendOrderNotificationToAdmin($order){
        $adminPhone = $this->messageHelper->getAdminPhone();
        if(!$adminPhone){
            Log::debug('Admin Mobile Number Not configured');
            return;
        }
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendOrderCancelNotificationToAdmin()) return;
        $message = $this->messageHelper->getCancelOrderMessage($order);
        $this->messageHelper->sendMessage($adminPhone,$message);
    }

    public function sendOrderNotificationToCustomer($order){
        $customer = $order->customer;
        if(!$customer){
            Log::debug('No Customer for order '.$order->id);
            return;
        }
        $userPhone = $this->getRecieverPhone($order,$customer);
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendCancelOrderNotificationCustomer()) return;
        $message = $this->messageHelper->getOrderCancelMessageCustomer($order);
        foreach ($userPhone as $phone){
            $this->messageHelper->sendMessage($phone,$message);
        }
    }

    public function AfterShipment($shipment){
        $this->sendShipmentNotificationToAdmin($shipment);
        $this->sendShipmentNotificationToCustomer($shipment);
    }

    public function  sendShipmentNotificationToAdmin($shipment){
        $adminPhone = $this->messageHelper->getAdminPhone();
        if(!$adminPhone){
            Log::debug('Admin Mobile Number Not configured');
            return;
        }
        $order = $shipment->order;
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendShipmentNotificationToAdmin()) return;
        $message = $this->messageHelper->getShipmentMessageAdmin($order,$shipment);
        $this->messageHelper->sendMessage($adminPhone,$message);
    }

    public function sendShipmentNotificationToCustomer($shipment){
        $order = $shipment->order;
        $customer = $order->customer;
        if(!$customer){
            Log::debug('No Customer for order '.$order->id);
            return;
        }
        $userPhone = $this->getRecieverPhone($order,$customer);
        if(!$this->messageHelper->extensionEnabled()) return;
        if(!$this->messageHelper->sendShipmentNotificationCustomer()) return;
        $message = $this->messageHelper->getShipmentMessageCustomer($order,$shipment);
        foreach ($userPhone as $phone){
            $this->messageHelper->sendMessage($phone,$message);
        }
    }
}