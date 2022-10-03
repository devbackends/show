<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class CountryOption
{
  /** @var int */
  protected $countryCode;

  /** @var string */
  protected $countryName;

  /** @var bool */
  protected $isDisplayed;

  /** @var string */
  protected $iso2;

  /** @var string */
  protected $iso3;

  public function __construct($data)
  {
    $this->countryCode = $data['countryCode'];
    $this->countryName = $data['countryName'];
    $this->isDisplayed = $data['isDisplayed'];
    $this->iso2 = $data['iso2'];
    $this->iso3 = $data['iso3'];
  }

  /**
   * @return int
   */
  public function getCountryCode()
  {
    return $this->countryCode;
  }

  /**
   * @return string
   */
  public function getCountryName()
  {
    return $this->countryName;
  }

  /**
   * @return bool
   */
  public function isDisplayed()
  {
    return $this->isDisplayed;
  }

  /**
   * @return string
   */
  public function getIso2()
  {
    return $this->iso2;
  }

  /**
   * @return string
   */
  public function getIso3()
  {
    return $this->iso3;
  }

}