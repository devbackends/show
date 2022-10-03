<?php


namespace Devvly\OnBoarding\Clearent\Models\Pricing;


use Devvly\OnBoarding\Clearent\Models\Model;

class PricingPlan extends Model
{

  use PricingPlanAttributes;

  /**
   * @param  PricingPlanFee[]  $pricingFees
   */
  public function setPricingFees(array $pricingFees): void
  {
    $this->pricingFees = $pricingFees;
  }

  /**
   * @param  int  $pricingPlanID
   */
  public function setPricingPlanID(int $pricingPlanID): void
  {
    $this->pricingPlanID = $pricingPlanID;
  }

  /**
   * @param  int  $pricingPlanTemplateID
   */
  public function setPricingPlanTemplateID(int $pricingPlanTemplateID): void
  {
    $this->pricingPlanTemplateID = $pricingPlanTemplateID;
  }

  /**
   * @param  string  $merchantNumber
   */
  public function setMerchantNumber(string $merchantNumber): void
  {
    $this->merchantNumber = $merchantNumber;
  }

  /**
   * @param  int  $discountQualificationRangeID
   */
  public function setDiscountQualificationRangeID(
      int $discountQualificationRangeID
  ): void {
    $this->discountQualificationRangeID = $discountQualificationRangeID;
  }

  /**
   * @param  int  $signatureDebitDiscountQualificationRangeID
   */
  public function setSignatureDebitDiscountQualificationRangeID(
      int $signatureDebitDiscountQualificationRangeID
  ): void {
    $this->signatureDebitDiscountQualificationRangeID = $signatureDebitDiscountQualificationRangeID;
  }

  /**
   * @param  string  $pricingTypeCode
   */
  public function setPricingTypeCode(string $pricingTypeCode): void
  {
    $this->pricingTypeCode = $pricingTypeCode;
  }

  /**
   * @param  bool  $isAdvancedPricing
   */
  public function setIsAdvancedPricing(bool $isAdvancedPricing): void
  {
    $this->isAdvancedPricing = $isAdvancedPricing;
  }

  /**
   * @param  bool  $isEMF
   */
  public function setIsEMF(bool $isEMF): void
  {
    $this->isEMF = $isEMF;
  }

  /**
   * @param  bool  $isDailySettle
   */
  public function setIsDailySettle(bool $isDailySettle): void
  {
    $this->isDailySettle = $isDailySettle;
  }

  /**
   * @param  bool  $includeAssessments
   */
  public function setIncludeAssessments(bool $includeAssessments): void
  {
    $this->includeAssessments = $includeAssessments;
  }

  /**
   * @param  string  $modifyDateTimeUtc
   */
  public function setModifyDateTimeUtc(string $modifyDateTimeUtc): void
  {
    $this->modifyDateTimeUtc = $modifyDateTimeUtc;
  }

}