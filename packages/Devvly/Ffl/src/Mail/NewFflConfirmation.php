<?php

namespace Devvly\Ffl\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Devvly\Ffl\Models\FflBusinessInfo;

/**
 * New Order Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewFflConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $email_data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_data)
    {
        $this->email_data=$email_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fflBusinessInfo=FflBusinessInfo::where('ffl_id',$this->email_data->id)->first();
        $data['company_name']=$fflBusinessInfo->company_name;
        $data['contact_name']=$fflBusinessInfo->contact_name;
        $data['token']=$this->email_data->token;
        return $this->to($fflBusinessInfo->email, $fflBusinessInfo->company_name)
            ->subject("Welcome to 2A Gun Show's Preferred FFL Network!")
            ->view('ffl::emails.preferred-ffl-confirmation-email')->with($data);
    }
}
