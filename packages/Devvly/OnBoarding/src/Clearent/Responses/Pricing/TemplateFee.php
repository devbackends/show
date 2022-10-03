<?php


namespace Devvly\OnBoarding\Clearent\Responses\Pricing;


use Devvly\OnBoarding\Clearent\Responses\Response;

class TemplateFee extends Response
{
  /** @var int */
  protected $clearentPricingFeeID;

  /** @var string */
  protected $clearentPricingFeeDescription;

  /** @var bool */
  protected $isEditable;

  /** @var bool */
  protected $isRequired;

  /** @var bool */
  protected $isVisible;

  /** @var bool */
  protected $isFee;

  /** @var bool */
  protected $isRate;

  /** @var bool */
  protected $isPayInMonthRequired1;

  /** @var bool */
  protected $isPayInMonthRequired2;

  /** @var float */
  protected $defaultRate;

  /** @var float */
  protected $minRate;

  /** @var float */
  protected $maxRate;

  /** @var int */
  protected $defaultFee;

  /** @var float */
  protected $minFee;

  /** @var float */
  protected $maxFee;

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
  public function getClearentPricingFeeDescription(): string
  {
    return $this->clearentPricingFeeDescription;
  }

  /**
   * @return bool
   */
  public function isEditable(): bool
  {
    return $this->isEditable;
  }

  /**
   * @return bool
   */
  public function isRequired(): bool
  {
    return $this->isRequired;
  }

  /**
   * @return bool
   */
  public function isVisible(): bool
  {
    return $this->isVisible;
  }

  /**
   * @return bool
   */
  public function isFee(): bool
  {
    return $this->isFee;
  }

  /**
   * @return bool
   */
  public function isRate(): bool
  {
    return $this->isRate;
  }

  /**
   * @return bool
   */
  public function isPayInMonthRequired1(): bool
  {
    return $this->isPayInMonthRequired1;
  }

  /**
   * @return bool
   */
  public function isPayInMonthRequired2(): bool
  {
    return $this->isPayInMonthRequired2;
  }

  /**
   * @return float
   */
  public function getDefaultRate()
  {
    return $this->defaultRate;
  }

  /**
   * @return float
   */
  public function getMinRate()
  {
    return $this->minRate;
  }

  /**
   * @return float
   */
  public function getMaxRate()
  {
    return $this->maxRate;
  }

  /**
   * @return float
   */
  public function getDefaultFee()
  {
    return $this->defaultFee;
  }

  /**
   * @return float
   */
  public function getMinFee()
  {
    return $this->minFee;
  }

  /**
   * @return float
   */
  public function getMaxFee()
  {
    return $this->maxFee;
  }
}