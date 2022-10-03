<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\ContactTypeAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class ContactType extends Response
{
  /** @var int */
  protected $contactTypeID;
  /** @var string */
  protected $contactTypeDescription;

  public function __construct($data)
  {
    parent::__construct($data);
  }

  /**
   * {@inheritDoc}
   */
  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if ($underscore_keys) {
      unset($data['contact_type_i_d']);
      $data['contact_type_id'] = $this->contactTypeID;
    }
    return $data;
  }

  /**
   * @return int
   */
  public function getContactTypeID(): int
  {
    return $this->contactTypeID;
  }

  /**
   * @return string
   */
  public function getContactTypeDescription(): string
  {
    return $this->contactTypeDescription;
  }
}