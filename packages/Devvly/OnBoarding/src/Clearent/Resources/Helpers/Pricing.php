<?php


namespace Devvly\OnBoarding\Clearent\Resources\Helpers;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Pricing\PricingPlan;
use Devvly\OnBoarding\Clearent\Models\Pricing\PricingPlanFee;
use Devvly\OnBoarding\Clearent\Resources\IResource;
use Devvly\OnBoarding\Clearent\Resources\Resources;
use Devvly\OnBoarding\Clearent\Responses\Pricing\PricingPlanTemplate;
use Devvly\OnBoarding\Clearent\Responses\Pricing\TemplateFee;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class Pricing extends IResource
{
  /** @var Resources */
  protected $resources;

  public function __construct(Client $client, Resources $resources)
  {
    parent::__construct($client);
    $this->resources = $resources;
  }

  /**
   * @param $number
   *
   * @throws ClearentException
   */
  public function createPlan($number)
  {
    $templates = $this->resources->pricingPlans()->getTemplates($number);

    // template: Flat - ISO - 6588000000000015
    // template ID: 1019
    /** @var PricingPlanTemplate $template */
    $template = null;
    foreach ($templates as $tem) {
      if($tem->getPricingPlanTemplateID() === 1019){
        $template = $tem;
        break;
      }
    }
    $pricingPlanTemplateId = $template->getPricingPlanTemplateID();
    $pricingTypeCode = $template->getPricingTypeCode();
    $isAdvancedPricing = $template->isAdvancedPricing();
    $isEmf = true;
    $isDailySettle = true;
    $includeAssessments = false;
    foreach ($template->getTemplateSettings() as $setting) {
      $name = $setting->getSettingName();
      if($name === "IncludeAssessments"){
        $includeAssessments = $setting->defaultValue();
      }
    }
    $fees = $this->setupFees($template);

    $plan = new PricingPlan();
    $plan->setPricingPlanTemplateID($pricingPlanTemplateId);
    $plan->setMerchantNumber($number);
    $plan->setPricingTypeCode($pricingTypeCode);
    $plan->setIsAdvancedPricing($isAdvancedPricing);
    $plan->setIsEMF($isEmf);
    $plan->setIsDailySettle($isDailySettle);
    $plan->setIncludeAssessments($includeAssessments);
    $plan->setPricingFees($fees);
    $res = $this->resources->pricingPlans()->createPlan($number, $plan);
  }

  /**
   * @param PricingPlanTemplate $template
   * @return PricingPlanFee[]
   */
  public function setUpFees($template){
    $fees = [];
    foreach ($template->getTemplateFees() as $fee) {
      $createdFee = $this->feeFromTemplate($fee);
      $include = false;
      switch ($fee->getClearentPricingFeeID()){
        // credit/debit cards:
        case 1: // MasterCard Debit
        case 2: // Visa Debit
        case 3: // MasterCard Credit
        case 4: // Visa Credit
        case 74: // Discover Debit
        case 75: // Discover Credit
        case 149: // Amex Qualified Credit
        case 150: // Amex Qualified Prepaid
          // @todo: set rate to 2.72%
          $createdFee->setRate(0.1);
          $include = true;
          break;
        case 83: // PIN Debit
          $createdFee->setFee(0.5);
          $include = true;
          break;
        // other:
        case 9: // EBT Per Item Fee
          $createdFee->setFee(0.05);
          $include = true;
          break;
        case 13: // Auth Fee
          $createdFee->setFee(0.3);
          $include = true;
          break;
        case 15: // Voice Authorization Fee
          $createdFee->setFee(1.0);
          $include = true;
          break;
        case 16: // Chargeback Fee
          $createdFee->setFee(25.0);
          $include = true;
          break;
        case 17: // Retrieval Fee
          $createdFee->setFee(10.0);
          $include = true;
          break;
        case 18: // Monthly Account Fee
          $createdFee->setFee(10.0);
          $include = true;
          break;
        case 29: // IVR Authorization
          $createdFee->setFee(0.5);
          $include = true;
          break;
        case 42: // Batch Processing Fee
          $createdFee->setFee(0.1);
          $include = true;
          break;
        case 118: // Express Merchant Funding Rate (IMF %):
          // todo: set rate to 0.04
          $createdFee->setRate(0.01);
          $include = true;
          break;
        case 174: // DataGuardian Fee
          $createdFee->setFee(12.0);
          $include = true;
          break;
        case 211: // PCI Non Compliance Fee
          $createdFee->setFee(25.0);
          $include = true;
          break;
        default:
          //
          break;
      }
      if($include){
        $fees[] = $createdFee;
      }
    }
    return $fees;
  }
  /**
   * @param TemplateFee $feeFromTemplate
   *
   * @return PricingPlanFee
   */
  protected function feeFromTemplate($feeFromTemplate)
  {
    $feeObj = new PricingPlanFee();
    $feeObj->setClearentPricingFeeID($feeFromTemplate->getClearentPricingFeeID());
    $feeObj->setPricingFeeDescription($feeFromTemplate->getClearentPricingFeeDescription());
    $feeObj->setRate($feeFromTemplate->getDefaultRate());
    $feeObj->setFee($feeFromTemplate->getDefaultFee());

    if($feeFromTemplate->isFee() && is_null($feeObj->getFee()))
    {
      $feeObj->setFee(0.0);
    }

    if($feeFromTemplate->isRate() && is_null($feeObj->getRate()))
    {
      $feeObj->setRate(0.0);
    }

    if($feeFromTemplate->isPayInMonthRequired1()){
      $feeObj->setPayInMonth1(1);
    }

    if($feeFromTemplate->isPayInMonthRequired2()){
      $feeObj->setPayInMonth2(1);
    }

    return $feeObj;
  }
}