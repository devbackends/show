<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


trait ContactTypeAttributes
{
  /** @var int */
  protected $businessContactContactTypeID;

  /** @var int */
  protected $businessContactID;

  /** @var int */
  protected $contactTypeID;

  /** @var string */
  protected $contactTypeDescription;

  /**
   * @return int
   */
  public function getBusinessContactContactTypeID()
  {
    return $this->businessContactContactTypeID;
  }

  /**
   * @return int
   */
  public function getBusinessContactID()
  {
    return $this->businessContactID;
  }

  /**
   * @return int
   */
  public function getContactTypeID()
  {
    return $this->contactTypeID;
  }

  /**
   * @return string
   */
  public function getContactTypeDescription()
  {
    return $this->contactTypeDescription;
  }

}