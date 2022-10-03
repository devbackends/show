<?php


namespace Devvly\Clearent\Models;


class CustomerToken extends Token
{

  /** @var string */
  protected $customerKey;

  /** @var bool */
  protected $default;

  public function __construct(string $payloadType, $content, $links = null)
  {
    parent::__construct($payloadType, $content, $links);

    $this->customerKey = $content['customer-key'];
    $this->default = $content['default'];
  }
}