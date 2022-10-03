<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Webkul\Marketplace\Helpers\SellerHelper;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class NewShipmentNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The shipment instance.
     *
     * @var \Webkul\Sales\Contracts\Shipment
     */
    public $shipment;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Shipment  $shipment
     * @return void
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->shipment->order;
        $helper = new SellerHelper();
        $seller = $helper->getSeller($order);
        $query = [
            'seller' => $seller->customer_id,
            'buyer' => $order->customer_id,
            'order' => $order->id,
        ];
        $contact_link = route('marketplace.account.messages.send-message-mail', $query);

        try{
            $request = [
                "from" => [
                    "email" => core()->getSenderEmailDetails()['email'],
                    "name" =>  core()->getSenderEmailDetails()['name']
                ],
                "to" => [
                    [
                        "email" => $order->customer_email,
                        "name" =>  $order->customer_full_name
                    ]
                ],
                "subject" => trans('shop::app.mail.shipment.subject', ['order_id' => $order->increment_id]),
                "html" => view('shop::emails.sales.new-shipment')->with(['seller' => $seller, 'contact_link' => $contact_link,'shipment' => $this->shipment])->render()
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

    public function restoreModel($value)
    {
        $value->relations = [];
        return $this->getQueryForModelRestoration(
            (new $value->class)->setConnection($value->connection), $value->id
        )->useWritePdo()->firstOrFail()->load($value->relations ?? []);
    }
}
