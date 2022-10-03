<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class StateOption
{
  /** @var string */
  protected $stateCode;

  /** @var string */
  protected $stateName;

  /** @var bool */
  protected $isDisplayed;

  public function __construct($data)
  {
    $this->stateCode = $data['stateCode'];
    $this->stateName = $data['stateName'];
    $this->isDisplayed = $data['isDisplayed'];
  }

  /**
   * @return int
   */
  public function getStateCode()
  {
    return $this->stateCode;
  }

  /**
   * @return string
   */
  public function getStateName()
  {
    return $this->stateName;
  }

  /**
   * @return bool
   */
  public function isDisplayed()
  {
    return $this->isDisplayed;
  }
}