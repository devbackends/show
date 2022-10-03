<?php


namespace Devvly\OnBoarding\Clearent\Responses\Helpers;


use Devvly\OnBoarding\Clearent\Models\Helpers\BusinessInformationAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class BusinessInformation extends Response
{
  use BusinessInformationAttributes;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->fromArray($data);
  }

  public function fromArray($data)
  {
    $this->merchant = $data['merchant'];
    $this->physicalAddress = $data['physicalAddress'];
    $this->mailingAddress = $data['mailingAddress'];
    $this->businessContacts = $data['businessContacts'];
    $this->taxPayer = $data['taxPayer'];
  }
}