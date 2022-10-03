<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\AddressAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class Address extends Response
{

  use AddressAttributes;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->setAttributes($data);
  }

  protected function setAttributes($data)
  {
    $this->line1 = $data['line1'];
    $this->line2 = $data['line2'];
    $this->line3 = $data['line3'];
    $this->city = $data['city'];
    $this->stateCode = $data['stateCode'];
    $this->countryCode = $data['countryCode'];
    $this->stateCode = $data['stateCode'];
  }


}