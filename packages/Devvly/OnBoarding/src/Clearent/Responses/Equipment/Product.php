<?php


namespace Devvly\OnBoarding\Clearent\Responses\Equipment;


use Devvly\OnBoarding\Clearent\Responses\Response;

class Product extends Response
{
  /** @var array */
  protected $frontends;

  /** @var string */
  protected $manufacturer;

  /** @var string */
  protected $productName;

  /** @var string */
  protected $productType;

  /**
   * @return array?
   */
  public function getFrontends()
  {
    return $this->frontends;
  }

  /**
   * @return string
   */
  public function getManufacturer(): string
  {
    return $this->manufacturer;
  }

  /**
   * @return string
   */
  public function getProductName(): string
  {
    return $this->productName;
  }

  /**
   * @return string
   */
  public function getProductType(): string
  {
    return $this->productType;
  }

}