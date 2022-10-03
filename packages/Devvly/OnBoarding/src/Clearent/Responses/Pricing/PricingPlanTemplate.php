<?php


namespace Devvly\OnBoarding\Clearent\Responses\Pricing;


use Devvly\OnBoarding\Clearent\Responses\Response;

class PricingPlanTemplate extends Response
{
  /** @var int */
  protected $pricingPlanTemplateID;

  /** @var string */
  protected $hierarchyNodeKey;

  /** @var string */
  protected $templateName;

  /** @var string */
  protected $pricingTypeCode;

  /** @var bool */
  protected $isAdvancedPricing;

  /** @var bool */
  protected $isDefaultTemplate;

  /** @var TemplateFee[] */
  protected $templateFees;

  /** @var TemplateSetting[] */
  protected $templateSettings;

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
  public function getHierarchyNodeKey(): string
  {
    return $this->hierarchyNodeKey;
  }

  /**
   * @return string
   */
  public function getTemplateName(): string
  {
    return $this->templateName;
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
  public function isDefaultTemplate(): bool
  {
    return $this->isDefaultTemplate;
  }

  /**
   * @return \Devvly\OnBoarding\Clearent\Responses\Pricing\TemplateFee[]
   */
  public function getTemplateFees(): array
  {
    return $this->templateFees;
  }

  /**
   * @return \Devvly\OnBoarding\Clearent\Responses\Pricing\TemplateSetting[]
   */
  public function getTemplateSettings(): array
  {
    return $this->templateSettings;
  }



  /**
   * @param  array  $templateFees
   */
  public function setTemplateFees(array $templateFees): void
  {
    $fees = [];
    foreach ($templateFees as $templateFee) {
      $fees[] = new TemplateFee($templateFee);
    }
    $this->templateFees = $fees;
  }

  /**
   * @param  array  $templateSettings
   */
  public function setTemplateSettings(array $templateSettings): void
  {
    $settings = [];
    foreach ($templateSettings as $templateSetting) {
      $settings[] = new TemplateSetting($templateSetting);
    }
    $this->templateSettings = $settings;
  }
}