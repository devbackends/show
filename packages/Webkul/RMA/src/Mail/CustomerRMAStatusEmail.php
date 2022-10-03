<?php

namespace Webkul\RMA\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerRMAStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $rmaStatus;

    public function __construct($rmaStatus) {
        $this->rmaStatus = $rmaStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->rmaStatus['email'])
            ->subject('Status Updated')
            ->view('rma::emails.customers.status')->with($this->rmaStatus);
    }
}
