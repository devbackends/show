<?php


namespace Devvly\OnBoarding\Helpers;


use Devvly\OnBoarding\Clearent\Models\Merchant\Phone;

trait ClearentHelper
{
  public function generatePhone(string $number)
  {
    $phone = new Phone();
    $phoneData = explode('-', $number);
    $phone->setAreaCode($phoneData[0]);
    $phone->setPhoneNumber($phoneData[1]);
    if(isset($phoneData[2])){
      $phone->setExtension($phoneData[2]);
    }
    // 5. work
    $phone->setPhoneTypeCodeID(5);
    return $phone;
  }
}