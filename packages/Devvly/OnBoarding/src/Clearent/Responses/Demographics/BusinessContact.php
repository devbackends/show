<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\BusinessContactAttributes;
use Devvly\OnBoarding\Clearent\Responses\Merchant\Phone;
use Devvly\OnBoarding\Clearent\Responses\Response;

class BusinessContact extends Response
{
  use BusinessContactAttributes;

  public function __construct($data)
  {
    parent::__construct($data);

    $this->setAttributes($data);
  }

  protected function setAttributes($data)
  {
    if(isset($data['contact'])){
      $this->contact = new Contact($data['contact']);
    }
    if(isset($data['phoneNumbers'])){
      $this->phoneNumbers = [];
      foreach ($data['phoneNumbers'] as $phoneNumber) {
        $this->phoneNumbers[] = new Phone($phoneNumber);
      }
    }
    if(isset($data['contactTypes'])){
      $this->contactTypes = [];
      foreach ($data['contactTypes'] as $contactType) {
        $this->contactTypes[] = new ContactType($contactType);
      }
    }
  }
}