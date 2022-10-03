<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


trait PhoneAttributes
{
  /** @var int */
  protected $phoneTypeCodeID;

  /** @var string */
  protected $areaCode;

  /** @var string */
  protected $phoneNumber;

  /** @var string */
  protected $extension;


  /**
   * @return int
   */
  public function getPhoneTypeCodeID()
  {
    return $this->phoneTypeCodeID;
  }

  /**
   * @return string
   */
  public function getAreaCode()
  {
    return $this->areaCode;
  }

  /**
   * @return string
   */
  public function getPhoneNumber()
  {
    return $this->phoneNumber;
  }

  /**
   * @return string
   */
  public function getExtension()
  {
    return $this->extension;
  }

}