<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class Address extends Model
{
  use AddressAttributes;

  /**
   * @param  string  $line1
   */
  public function setLine1($line1)
  {
    $this->line1 = $line1;
  }

  /**
   * @param  string  $line2
   */
  public function setLine2($line2)
  {
    $this->line2 = $line2;
  }

  /**
   * @param  string  $line3
   */
  public function setLine3($line3)
  {
    $this->line3 = $line3;
  }

  /**
   * @param  string  $city
   */
  public function setCity($city)
  {
    $this->city = $city;
  }

  /**
   * @param  string  $stateCode
   */
  public function setStateCode($stateCode)
  {
    $this->stateCode = $stateCode;
  }

  /**
   * @param  string  $zip
   */
  public function setZip($zip)
  {
    $this->zip = $zip;
  }

  /**
   * @param  int  $countryCode
   */
  public function setCountryCode($countryCode)
  {
    $this->countryCode = $countryCode;
  }
}