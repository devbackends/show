<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class NewInvoiceNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The invoice instance.
     *
     * @param  \Webkul\Customer\Contracts\Invoice  $invoice
     */
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Invoice  $invoice
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $marketplace_order= app('Webkul\Marketplace\Repositories\OrderRepository')->findWhere(['order_id'=>$this->invoice->order->id])->first();
        if($marketplace_order){
            $seller_id=$marketplace_order->marketplace_seller_id;
        }else{
            $seller_id=0;
        }
        $marketplace_seller= app('Webkul\Marketplace\Repositories\SellerRepository')->findWhere(['id'=>$seller_id])->first();
        $this->invoice->order->seller=$marketplace_seller;
        $order = $this->invoice->order;
        $seller_email= app('Webkul\Customer\Repositories\CustomerRepository')->find($marketplace_seller->customer_id)->email;
        $this->invoice->order->seller->email=$seller_email;

        try{
            $request = [
                "from" => [
                    "email" => core()->getSenderEmailDetails()['email'],
                    "name" => core()->getSenderEmailDetails()['name']
                ],
                "to" => [
                    [
                        "email" =>$order->customer_email,
                        "name" =>  $order->customer_full_name
                    ]
                ],
                "subject" => trans('shop::app.mail.invoice.subject', ['order_id' => $order->increment_id]),
                "html" => view('shop::emails.sales.new-invoice')->with(['order' => $order,'seller_email'=>$seller_email,'invoice'=>$this->invoice])->render()
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
