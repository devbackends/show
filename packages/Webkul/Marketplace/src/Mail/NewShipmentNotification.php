<?php

namespace Webkul\Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

/**
 * New Shipment Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewShipmentNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The Shipment instance.
     *
     * @var Shipment
     */
    public $sellerShipment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sellerShipment)
    {
        $this->sellerShipment = $sellerShipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        try{
            $request = [
                "from" => [
                    "email" => "MS_AlWzQO@2agunshow.com",
                    "name" => "2agunshow"
                ],
                "to" => [
                    [
                        "email" => $this->sellerShipment->order->seller->customer->email,
                        "name"  => $this->sellerShipment->order->seller->customer->name
                    ]
                ],
                "subject" => trans('marketplace::app.mail.sales.shipment.subject'),
                "html" => view('marketplace::emails.sales.new-shipment')->with(['sellerShipment' => $this->sellerShipment])->render()
            ];

            $url = "https://api.mailersend.com/v1/email";
            $post = http_build_query($request);
            $ch = curl_init($url );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            $headers = array();
            $headers[] = "Authorization: Bearer ".getenv('MAILERSEND_API_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($ch);
            curl_close($ch);
            $resp = json_decode($resp,true);
        }catch (\Exception $e){

        }



    }
}
