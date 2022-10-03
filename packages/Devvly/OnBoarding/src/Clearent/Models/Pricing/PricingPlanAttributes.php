<?php


namespace Devvly\OnBoarding\Clearent\Models\Pricing;


trait PricingPlanAttributes
{
  /** @var PricingPlanFee[] */
  protected $pricingFees;

  /** @var int */
  protected $pricingPlanID;

  /** @var int */
  protected $pricingPlanTemplateID;

  /** @var string */
  protected $merchantNumber;

  /** @var int */
  protected $discountQualificationRangeID;

  /** @var int */
  protected $signatureDebitDiscountQualificationRangeID;

  /** @var string */
  protected $pricingTypeCode;

  /** @var bool */
  protected $isAdvancedPricing;

  /** @var bool */
  protected $isEMF;

  /** @var bool */
  protected $isDailySettle;

  /** @var bool */
  protected $includeAssessments;

  /** @var string */
  protected $modifyDateTimeUtc;

  /**
   * @return PricingPlanFee[]
   */
  public function getPricingFees(): array
  {
    return $this->pricingFees;
  }

  /**
   * @return int
   */
  public function getPricingPlanID(): int
  {
    return $this->pricingPlanID;
  }

  /**
   * @return int
   */
  public function getPricingPlanTemplateID(): int
  {
    return $this->pricingPlanTemplateID;
  }

  /**
   * @return string
   */
  public function getMerchantNumber(): string
  {
    return $this->merchantNumber;
  }

  /**
   * @return int
   */
  public function getDiscountQualificationRangeID(): int
  {
    return $this->discountQualificationRangeID;
  }

  /**
   * @return int
   */
  public function getSignatureDebitDiscountQualificationRangeID(): int
  {
    return $this->signatureDebitDiscountQualificationRangeID;
  }

  /**
   * @return string
   */
  public function getPricingTypeCode(): string
  {
    return $this->pricingTypeCode;
  }

  /**
   * @return bool
   */
  public function isAdvancedPricing(): bool
  {
    return $this->isAdvancedPricing;
  }

  /**
   * @return bool
   */
  public function isEMF(): bool
  {
    return $this->isEMF;
  }

  /**
   * @return bool
   */
  public function isDailySettle(): bool
  {
    return $this->isDailySettle;
  }

  /**
   * @return bool
   */
  public function isIncludeAssessments(): bool
  {
    return $this->includeAssessments;
  }

  /**
   * @return string
   */
  public function getModifyDateTimeUtc(): string
  {
    return $this->modifyDateTimeUtc;
  }

}