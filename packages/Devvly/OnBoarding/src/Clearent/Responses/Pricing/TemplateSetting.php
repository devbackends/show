<?php


namespace Devvly\OnBoarding\Clearent\Responses\Pricing;


use Devvly\OnBoarding\Clearent\Responses\Response;

class TemplateSetting extends Response
{
  /** @var int */
  protected $pricingPlanSettingID;

  /** @var string */
  protected $settingName;

  /** @var string */
  protected $description;

  /** @var bool */
  protected $isVisible;

  /** @var bool */
  protected $isEditable;

  /** @var bool */
  protected $defaultValue;

  /**
   * @return int
   */
  public function getPricingPlanSettingID(): int
  {
    return $this->pricingPlanSettingID;
  }

  /**
   * @return string
   */
  public function getSettingName(): string
  {
    return $this->settingName;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
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
  public function isEditable(): bool
  {
    return $this->isEditable;
  }

  /**
   * @return bool
   */
  public function defaultValue(): bool
  {
    return $this->defaultValue;
  }
}