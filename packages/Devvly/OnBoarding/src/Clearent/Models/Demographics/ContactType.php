<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class ContactType extends Model
{
  use ContactTypeAttributes;
  public function __construct($data = null)
  {
    parent::__construct($data);
  }

  /**
   * @param  int  $contactTypeID
   */
  public function setContactTypeID(int $contactTypeID): void
  {
    $this->contactTypeID = $contactTypeID;
  }

  /**
   * @param  string  $contactTypeDescription
   */
  public function setContactTypeDescription(string $contactTypeDescription
  ): void {
    $this->contactTypeDescription = $contactTypeDescription;
  }
}