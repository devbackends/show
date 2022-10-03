<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;


class NewInventorySourceNotification extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    /**
     * The shipment instance.
     *
     * @var \Webkul\Customer\Contracts\Shipment
     */
    public $shipment;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Shipment  $shipment
     * @return void
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

    }

    public function restoreModel($value)
    {
        $value->relations = [];
        return $this->getQueryForModelRestoration(
            (new $value->class)->setConnection($value->connection), $value->id
        )->useWritePdo()->firstOrFail()->load($value->relations ?? []);
    }
}
