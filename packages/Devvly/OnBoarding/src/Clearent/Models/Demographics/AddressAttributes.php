<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


trait AddressAttributes
{
  /** @var string */
  protected $line1;

  /** @var string */
  protected $line2;

  /** @var string */
  protected $line3;

  /** @var string */
  protected $city;

  /** @var string */
  protected $stateCode;

  /** @var string */
  protected $zip;

  /** @var int */
  protected $countryCode;

  /**
   * @return string
   */
  public function getLine1()
  {
    return $this->line1;
  }

  /**
   * @return string
   */
  public function getLine2()
  {
    return $this->line2;
  }

  /**
   * @return string
   */
  public function getLine3()
  {
    return $this->line3;
  }

  /**
   * @return string
   */
  public function getCity()
  {
    return $this->city;
  }

  /**
   * @return string
   */
  public function getStateCode()
  {
    return $this->stateCode;
  }

  /**
   * @return string
   */
  public function getZip()
  {
    return $this->zip;
  }

  /**
   * @return int
   */
  public function getCountryCode()
  {
    return $this->countryCode;
  }
}