<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class TermsAndCondition extends Model
{
  /** @var int */
  protected $termsAndConditionsID;

  /** @var int */
  protected $typeID;

  /** @var string */
  protected $text;

  /** @var string */
  protected $effectiveDateUTC;

  /** @var string */
  protected $description;

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if($underscore_keys){
      unset($data['terms_and_conditions_i_d']);
      $data['terms_and_conditions_id'] = $this->termsAndConditionsID;
      unset($data['type_i_d']);
      $data['type_id'] = $this->typeID;
      unset($data['effective_date_u_t_c']);
      $data['effective_date_utc'] = $this->effectiveDateUTC;
    }
    return $data;
  }

  /**
   * @return int
   */
  public function getTermsAndConditionsID(): int
  {
    return $this->termsAndConditionsID;
  }

  /**
   * @return int
   */
  public function getTypeID(): int
  {
    return $this->typeID;
  }

  /**
   * @return string
   */
  public function getText(): string
  {
    return $this->text;
  }

  /**
   * @return string
   */
  public function getEffectiveDateUTC(): string
  {
    return $this->effectiveDateUTC;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
  }
}