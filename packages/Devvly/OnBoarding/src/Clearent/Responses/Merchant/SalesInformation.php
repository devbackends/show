<?php


namespace Devvly\OnBoarding\Clearent\Responses\Merchant;


use Devvly\OnBoarding\Clearent\Models\Merchant\SalesInformationAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class SalesInformation extends Response
{
  use SalesInformationAttributes;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->businessID = $data['businessID'];
    $this->assignedUser = $data['assignedUser'];
    $this->referralPartner = $data['referralPartner'];
    $this->compensationType = $data['compensationType'];
  }
}