<?php

namespace Webkul\Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;


class UserHelpNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    const TO_EMAIL = 'support@2acommerce.zohodesk.com';

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
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
                    "email" => $this->message['email'],
                    "name"  =>  $this->message['name']
                ],
                "to" => [
                    [
                        "email" => self::TO_EMAIL,
                        "name" => $this->sellerOrder->seller->customer->first_name.' '.$this->sellerOrder->seller->customer->last_name
                    ]
                ],
                "subject" => 'Help Request from '.$this->message['name']. '('.$this->message['email'].')',
                "html" => view('marketplace::shop.emails.user-help')->with( ['msg' => $this->message])->render()
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
