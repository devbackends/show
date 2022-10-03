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
 * Product Approval Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDisapprovalNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The Product instance.
     *
     * @var Product
     */
    public $sellerProduct;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sellerProduct)
    {
        $this->sellerProduct = $sellerProduct;
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
                        "email" => $this->sellerProduct->seller->customer->email,
                        "name" =>  $this->sellerProduct->seller->customer->name
                    ]
                ],
                "subject" => trans('marketplace::app.mail.product.disapprove-product'),
                "html" => view('marketplace::emails.product.disapproval')->with(['sellerProduct' => $this->sellerProduct])->render()
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
