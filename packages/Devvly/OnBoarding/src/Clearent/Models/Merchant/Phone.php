<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


use Devvly\OnBoarding\Clearent\Models\Model;

class Phone extends Model
{
  use PhoneAttributes;

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if($underscore_keys){
      unset($data['phone_type_code_i_d']);
      $data['phone_type_code_id'] = $this->phoneTypeCodeID;
    }
    return $data;
  }

  /**
   * @param  int  $phoneTypeCodeID
   */
  public function setPhoneTypeCodeID($phoneTypeCodeID)
  {
    $this->phoneTypeCodeID = $phoneTypeCodeID;
  }

  /**
   * @param  string  $areaCode
   */
  public function setAreaCode($areaCode)
  {
    $this->areaCode = $areaCode;
  }

  /**
   * @param  string  $phoneNumber
   */
  public function setPhoneNumber($phoneNumber)
  {
    $this->phoneNumber = $phoneNumber;
  }

  /**
   * @param  string  $extension
   */
  public function setExtension($extension)
  {
    $this->extension = $extension;
  }

}