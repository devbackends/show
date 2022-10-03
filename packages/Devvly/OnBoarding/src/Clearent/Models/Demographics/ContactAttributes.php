<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;

trait ContactAttributes
{
  /** @var string */
  protected $firstName;

  /** @var string */
  protected $lastName;

  /** @var string */
  protected $ssn;

  /** @var string */
  protected $dateOfBirth;

  /** @var int */
  protected $countryOfCitizenshipCode;

  /** @var Address */
  protected $address;

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /**
   * @return string
   */
  public function getSsn(): string
  {
    return $this->ssn;
  }

  /**
   * @return string
   */
  public function getDateOfBirth(): string
  {
    return $this->dateOfBirth;
  }

  /**
   * @return int
   */
  public function getCountryOfCitizenshipCode(): int
  {
    return $this->countryOfCitizenshipCode;
  }

  /**
   * @return Address
   */
  public function getAddress(): Address
  {
    return $this->address;
  }

}