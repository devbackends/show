<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


trait TaxPayerAttributes
{
  /** @var string */
  protected $legalFirstName;

  /** @var string */
  protected $legalLastName;

  /** @var int */
  protected $tin;

  /** @var int */
  protected $tinTypeID;

  /** @var string */
  protected $businessLegalName;

  /** @var string */
  protected $stateIncorporatedCode;

  /**
   * @return string
   */
  public function getLegalFirstName(): string
  {
    return $this->legalFirstName;
  }

  /**
   * @return string
   */
  public function getLegalLastName(): string
  {
    return $this->legalLastName;
  }

  /**
   * @return int
   */
  public function getTin(): int
  {
    return $this->tin;
  }

  /**
   * @return int
   */
  public function getTinTypeID(): int
  {
    return $this->tinTypeID;
  }

  /**
   * @return string
   */
  public function getBusinessLegalName(): string
  {
    return $this->businessLegalName;
  }

  /**
   * @return string
   */
  public function getStateIncorporatedCode(): string
  {
    return $this->stateIncorporatedCode;
  }

}