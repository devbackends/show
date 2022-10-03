<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class Contact extends Model
{

  use ContactAttributes;

  /**
   * @param  string  $firstName
   */
  public function setFirstName(string $firstName): void
  {
    $this->firstName = $firstName;
  }

  /**
   * @param  string  $lastName
   */
  public function setLastName(string $lastName): void
  {
    $this->lastName = $lastName;
  }

  /**
   * @param  string  $ssn
   */
  public function setSsn(string $ssn): void
  {
    $this->ssn = $ssn;
  }

  /**
   * @param  string  $dateOfBirth
   */
  public function setDateOfBirth(string $dateOfBirth): void
  {
    $this->dateOfBirth = $dateOfBirth;
  }

  /**
   * @param  int  $countryOfCitizenshipCode
   */
  public function setCountryOfCitizenshipCode(int $countryOfCitizenshipCode
  ): void {
    $this->countryOfCitizenshipCode = $countryOfCitizenshipCode;
  }

  /**
   * @param  Address  $address
   */
  public function setAddress($address): void
  {
    if (!$address instanceof Address) {
      $address = new Address($address);
    }
    $this->address = $address;
  }

}