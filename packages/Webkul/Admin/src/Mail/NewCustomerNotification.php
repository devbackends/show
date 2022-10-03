<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;



class NewCustomerNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The customer instance.
     *
     * @var  \Webkul\Customer\Contracts\Customer
     */
    public $customer;

    /**
     * The password instance.
     *
     * @var string
     */
    public $password;

    /**
     * Create a new message instance.
     * 
     * @param  \Webkul\Customer\Contracts\Customer  $order
     * @param  string  $password
     * @return void
     */
    public function __construct(
        $customer,
        $password
    )
    {
        $this->customer = $customer;

        $this->password = $password;
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
                        "email" => $this->customer->email,
                        "name" => $this->customer->first_name.' '.$this->customer->last_name
                    ]
                ],
                "subject" => trans('shop::app.mail.customer.new.subject'),
                "html" => view('shop::emails.customer.new-customer')->with(['customer' => $this->customer, 'password' => $this->password])->render()
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