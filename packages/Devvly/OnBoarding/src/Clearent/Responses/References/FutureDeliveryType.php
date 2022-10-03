<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class FutureDeliveryType
{
  /** @var int */
  protected $futureDeliveryTypeID;

  /** @var string */
  protected $futureDeliveryTypeDescription;


  public function __construct($data)
  {
    $this->futureDeliveryTypeID = $data['futureDeliveryTypeID'];
    $this->futureDeliveryTypeDescription = $data['futureDeliveryTypeDescription'];
  }

  /**
   * @return int
   */
  public function getFutureDeliveryTypeId()
  {
    return $this->futureDeliveryTypeID;
  }

  /**
   * @return string
   */
  public function getFutureDeliveryTypeDescription()
  {
    return $this->futureDeliveryTypeDescription;
  }
}