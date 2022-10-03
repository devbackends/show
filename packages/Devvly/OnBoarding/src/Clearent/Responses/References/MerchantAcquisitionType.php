<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class MerchantAcquisitionType
{
  /** @var int */
  protected $merchantAcquisitionTypeID;

  /** @var string */
  protected $merchantAcquisitionType;


  public function __construct($data)
  {
    $this->merchantAcquisitionTypeID = $data['merchantAcquisitionTypeID'];
    $this->merchantAcquisitionType = $data['merchantAcquisitionType'];
  }

  /**
   * @return int
   */
  public function getMerchantAcquisitionTypeID(): int
  {
    return $this->merchantAcquisitionTypeID;
  }

  /**
   * @return string
   */
  public function getMerchantAcquisitionType(): string
  {
    return $this->merchantAcquisitionType;
  }
}