<?php


namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class CancelOrderNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * @var \Webkul\Sales\Contracts\Order
     */
    public $order;

    /**
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {


        try{
            $request = [
                "from" => [
                    "email" => core()->getSenderEmailDetails()['email'],
                    "name" => core()->getSenderEmailDetails()['name']
                ],
                "to" => [
                    [
                        "email" => $this->order->customer_email,
                        "name" =>  $this->order->customer_full_name
                    ]
                ],
                "subject" => trans('shop::app.mail.order.cancel.subject'),
                "html" => view('shop::emails.sales.order-cancel')->with(['order' => $this->order])->render()
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