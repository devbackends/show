<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Marketplace\Helpers\SellerHelper;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class NewAdminNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The order instance.
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
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

        $order = $this->order;
        $helper = new SellerHelper();
        $seller = $helper->getSeller($order);
        $query = [
            'seller' => $seller->customer_id,
            'buyer' => $order->customer_id,
            'order' => $order->id,
        ];
        $contact_link = route('marketplace.account.messages.send-message-mail', $query);
        $sender = core()->getSenderEmailDetails();
        $adminEmail = core()->getAdminEmailDetails();

        try{
            $request = [
                "from" => [
                    "email" => $sender['email'],
                    "name" =>  $sender['name']
                ],
                "to" => [
                    [
                        "email" => $adminEmail['email'],
                        "name" =>  $adminEmail['email']
                    ]
                ],
                "subject" => trans('shop::app.mail.order.subject'),
                "html" => view('shop::emails.sales.new-admin-order')->with(['contact_link'=> $contact_link, 'seller' => $seller])->render()
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