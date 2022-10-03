<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class PhoneType
{
  /** @var int */
  protected $phoneTypeCodeID;

  /** @var string */
  protected $phoneTypeDescription;

  /** @var int */
  protected $displayOrder;

  public function __construct($data)
  {
    $this->phoneTypeCodeID = $data['phoneTypeCodeID'];
    $this->phoneTypeDescription = $data['phoneTypeDescription'];
    $this->displayOrder = $data['displayOrder'];
  }

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
  public function getPhoneTypeDescription()
  {
    return $this->phoneTypeDescription;
  }

  /**
   * @return int
   */
  public function getDisplayOrder()
  {
    return $this->displayOrder;
  }
}