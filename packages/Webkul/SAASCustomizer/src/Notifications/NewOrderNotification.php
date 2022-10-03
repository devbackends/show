<?php

namespace Webkul\SAASCustomizer\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Webkul\Marketplace\Helpers\SellerHelper;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

/**
 * Order Agent Mail class
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {

            $order = $this->order;
            $helper = new SellerHelper();
            $seller = $helper->getSeller($order);
            $query = [
                'seller' => $seller->customer_id,
                'buyer' => $order->customer_id,
                'order' => $order->id,
            ];
            $contact_link = route('marketplace.account.messages.send-message-mail', $query);
            $request = [
                "from" => [
                    "email" => "MS_AlWzQO@2agunshow.com",
                    "name" => "2agunshow"
                ],
                "to" => [
                    [
                        "email" => company()->getSuperConfigData('general.agent.super.email'),
                        "name" => company()->getSuperConfigData('general.agent.super.email')
                    ]
                ],
                "subject" =>  trans('shop::app.mail.order.subject'),
                "html" =>  view('shop::emails.sales.new-order')->with(['order' => $this->order, 'seller' => $seller, 'contact_link' => $contact_link])->render()
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