<?php


namespace Devvly\OnBoarding\Clearent\Responses\Pricing;


use Devvly\OnBoarding\Clearent\Models\Pricing\PricingPlanAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;
use \Devvly\OnBoarding\Clearent\Models\Pricing\PricingPlanFee;

class PricingPlan extends Response
{
  use PricingPlanAttributes;

  /**
   * @param  PricingPlanFee[]  $pricingFees
   */
  public function setPricingFees(array $pricingFees): void
  {
    $this->pricingFees = [];
    foreach ($pricingFees as $pricingFee) {
      if(!$pricingFee instanceof PricingPlanFee){
        $pricingFee = new PricingPlanFee($pricingFee);
      }
      $this->pricingFees[] = $pricingFee;
    }
  }
}