<?php


namespace Devvly\OnBoarding\Clearent\Responses\Merchant;


use Devvly\OnBoarding\Clearent\Models\Merchant\PhoneAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class Phone extends Response
{
  use PhoneAttributes;

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if ($underscore_keys) {
      unset($data['phone_type_code_i_d']);
      $data['phone_type_code_id'] = $this->phoneTypeCodeID;
    }
    return $data;
  }
}