<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;


class NewRefundNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The refund instance.
     *
     * @var \Webkul\Sales\Contracts\Refund
     */
    public $refund;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function __construct($refund)
    {
        $this->refund = $refund;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->refund->order;

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
                "subject" => trans('shop::app.mail.refund.subject', ['order_id' => $order->increment_id]),
                "html" => view('shop::emails.sales.new-refund')->with(['refund' => $this->refund])->render()
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
