<?php


namespace Devvly\Clearent\Models;


class Customer extends Payload
{
  use CustomerProperties;

  public function __construct(string $payloadType, $content, $links = null)
  {
    parent::__construct($payloadType, $content, $links);

    $this->setCustomerKey($content['customer-key']);
    if (isset($content['first-name'])) {
      $this->setFirstName($content['first-name']);
    }
    if (isset($content['last-name'])) {
      $this->setLastName($content['last-name']);
    }
  }


}