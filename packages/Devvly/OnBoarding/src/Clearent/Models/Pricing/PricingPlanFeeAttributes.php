<?php


namespace Devvly\OnBoarding\Clearent\Models\Pricing;


trait PricingPlanFeeAttributes
{
  /** @var int */
  protected $clearentPricingFeeID;

  /** @var string */
  protected $pricingFeeDescription;

  /** @var float */
  protected $rate;

  /** @var float */
  protected $fee;

  /** @var int */
  protected $payInMonth1;

  /** @var int */
  protected $payInMonth2;

  /** @var string */
  protected $modifyDateTimeUtc;

  /**
   * @return int
   */
  public function getClearentPricingFeeID(): int
  {
    return $this->clearentPricingFeeID;
  }

  /**
   * @return string
   */
  public function getPricingFeeDescription(): string
  {
    return $this->pricingFeeDescription;
  }

  /**
   * @return float
   */
  public function getRate()
  {
    return $this->rate;
  }

  /**
   * @return float
   */
  public function getFee()
  {
    return $this->fee;
  }

  /**
   * @return int
   */
  public function getPayInMonth1(): int
  {
    return $this->payInMonth1;
  }

  /**
   * @return int
   */
  public function getPayInMonth2(): int
  {
    return $this->payInMonth2;
  }

  /**
   * @return string
   */
  public function getModifyDateTimeUtc(): string
  {
    return $this->modifyDateTimeUtc;
  }
}