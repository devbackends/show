<?php


namespace Devvly\Clearent\Models;


class CustomerOptions
{
  use CustomerProperties;

  /**
   * CustomerOptions constructor.
   *
   * @param string $firstName
   * @param string $lastName
   */
  public function __construct(string $firstName, string $lastName)
  {
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }
}