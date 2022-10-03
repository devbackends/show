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
 * Seller registration mail to Admin
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewSellerNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The seller instance.
     *
     * @var Seller
     */
    public $seller;

    /**
     * The admin instance.
     *
     * @var Admin
     */
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($seller, $admin)
    {
        $this->seller = $seller;

        $this->admin = $admin;
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
                        "email" => $this->admin->email,
                        "name" =>  $this->admin->email
                    ]
                ],
                "subject" => trans('marketplace::app.mail.seller.regisration.subject'),
                "html" => view('marketplace::emails.seller.register')->render()
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