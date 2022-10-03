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
 * Contact Seller Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ContactSellerNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The seller instance.
     *
     * @var Seller
     */
    public $seller;

    /**
     * Contains form data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param Seller $seller
     * @param array  $data
     * @return void
     */
    public function __construct($seller, $data)
    {
        $this->seller = $seller;

        $this->data = $data;
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
                        "email" => $this->seller->customer->email,
                        "name" =>  $this->seller->customer->name
                    ]
                ],
                "subject" => trans('marketplace::app.shop.sellers.mails.contact-seller.subject', ['subject' => $this->data['subject']]),
                "html" => view('marketplace::shop.emails.contact-seller')->with(['sellerName' => $this->seller->name, 'query' => $this->data['query'],'name'=>$this->data['name'],'email'=>$this->data['email'],'subject'=>$this->data['subject']])->render()
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
