<?php

namespace Webkul\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class SubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    public $subscriptionData;

    /**
     * Create a mailable instance
     * 
     * @param  array  $subscriptionData
     */
    public function __construct($subscriptionData)
    {
        $this->subscriptionData = $subscriptionData;
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
                    "email" => core()->getSenderEmailDetails()['email'],
                    "name" =>  core()->getSenderEmailDetails()['name']
                ],
                "to" => [
                    [
                        "email" => $this->subscriptionData['email'],
                        "name" =>  $this->subscriptionData['email']
                    ]
                ],
                "subject" => trans('shop::app.mail.customer.subscription.subject'),
                "html" => view('shop::emails.customer.subscription-email')->with('data', [
                        'content' => 'You Are Subscribed',
                        'token'   => $this->subscriptionData['token'],
                    ]
                )->render()
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