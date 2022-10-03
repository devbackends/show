<?php


namespace Devvly\OnBoarding\Clearent\Models\Pricing;


use Devvly\OnBoarding\Clearent\Models\Model;

class PricingPlanFee extends Model
{

  use PricingPlanFeeAttributes;

  /**
   * @param  int  $clearentPricingFeeID
   */
  public function setClearentPricingFeeID(int $clearentPricingFeeID): void
  {
    $this->clearentPricingFeeID = $clearentPricingFeeID;
  }

  /**
   * @param  string  $pricingFeeDescription
   */
  public function setPricingFeeDescription(string $pricingFeeDescription): void
  {
    $this->pricingFeeDescription = $pricingFeeDescription;
  }

  /**
   * @param  float  $rate
   */
  public function setRate($rate): void
  {
    $this->rate = $rate;
  }

  /**
   * @param  float  $fee
   */
  public function setFee($fee): void
  {
    $this->fee = $fee;
  }

  /**
   * @param  int  $payInMonth1
   */
  public function setPayInMonth1(int $payInMonth1): void
  {
    $this->payInMonth1 = $payInMonth1;
  }

  /**
   * @param  int  $payInMonth2
   */
  public function setPayInMonth2(int $payInMonth2): void
  {
    $this->payInMonth2 = $payInMonth2;
  }

  /**
   * @param  string  $modifyDateTimeUtc
   */
  public function setModifyDateTimeUtc(string $modifyDateTimeUtc): void
  {
    $this->modifyDateTimeUtc = $modifyDateTimeUtc;
  }

}