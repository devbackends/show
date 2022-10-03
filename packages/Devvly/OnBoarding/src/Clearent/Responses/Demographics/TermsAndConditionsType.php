<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Responses\Response;

class TermsAndConditionsType extends Response
{
  /** @var int */
  protected $termsAndConditionsTypeID;

  /** @var string */
  protected $description;

  /**
   * @return int
   */
  public function getTermsAndConditionsTypeID(): int
  {
    return $this->termsAndConditionsTypeID;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
  }
}