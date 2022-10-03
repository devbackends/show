<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


trait seasonalScheduleAttributes
{
  /** @var bool */
  protected $january;

  /** @var bool */
  protected $february;

  /** @var bool */
  protected $march;

  /** @var bool */
  protected $april;

  /** @var bool */
  protected $may;

  /** @var bool */
  protected $june;

  /** @var bool */
  protected $july;

  /** @var bool */
  protected $august;

  /** @var bool */
  protected $september;

  /** @var bool */
  protected $october;

  /** @var bool */
  protected $november;

  /** @var bool */
  protected $december;

  /**
   * @return bool
   */
  public function isJanuary()
  {
    return $this->january;
  }

  /**
   * @return bool
   */
  public function isFebruary()
  {
    return $this->february;
  }

  /**
   * @return bool
   */
  public function isMarch()
  {
    return $this->march;
  }

  /**
   * @return bool
   */
  public function isApril()
  {
    return $this->april;
  }

  /**
   * @return bool
   */
  public function isMay()
  {
    return $this->may;
  }

  /**
   * @return bool
   */
  public function isJune()
  {
    return $this->june;
  }

  /**
   * @return bool
   */
  public function isJuly()
  {
    return $this->july;
  }

  /**
   * @return bool
   */
  public function isAugust()
  {
    return $this->august;
  }

  /**
   * @return bool
   */
  public function isSeptember()
  {
    return $this->september;
  }

  /**
   * @return bool
   */
  public function isOctober()
  {
    return $this->october;
  }

  /**
   * @return bool
   */
  public function isNovember()
  {
    return $this->november;
  }

  /**
   * @return bool
   */
  public function isDecember()
  {
    return $this->december;
  }





}