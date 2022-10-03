<?php


namespace Devvly\Clearent\Models;


trait CustomerProperties
{
  /** @var string */
  protected $customerKey;

  /** @var string */
  protected $firstName;

  /** @var string */
  protected $lastName;

  /**
   * @return string
   */
  public function getCustomerKey(): string
  {
    return $this->customerKey;
  }

  /**
   * @param  string  $customerKey
   */
  public function setCustomerKey(string $customerKey): void
  {
    $this->customerKey = $customerKey;
  }

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  /**
   * @param  string  $firstName
   */
  public function setFirstName(string $firstName): void
  {
    $this->firstName = $firstName;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /**
   * @param  string  $lastName
   */
  public function setLastName(string $lastName): void
  {
    $this->lastName = $lastName;
  }
}