<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class TaxPayer extends Model
{

  use TaxPayerAttributes;

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if ($underscore_keys) {
      unset($data['tin_type_i_d']);
      $data['tin_type_id'] = $this->tinTypeID;
    }
    return $data;
  }

  /**
   * @param  string  $legalFirstName
   */
  public function setLegalFirstName(string $legalFirstName): void
  {
    $this->legalFirstName = $legalFirstName;
  }

  /**
   * @param  string  $legalLastName
   */
  public function setLegalLastName(string $legalLastName): void
  {
    $this->legalLastName = $legalLastName;
  }

  /**
   * @param  int  $tin
   */
  public function setTin(int $tin): void
  {
    $this->tin = $tin;
  }

  /**
   * @param  int  $tinTypeID
   */
  public function setTinTypeID(int $tinTypeID): void
  {
    $this->tinTypeID = $tinTypeID;
  }

  /**
   * @param  string  $businessLegalName
   */
  public function setBusinessLegalName(string $businessLegalName): void
  {
    $this->businessLegalName = $businessLegalName;
  }

  /**
   * @param  string  $stateIncorporatedCode
   */
  public function setStateIncorporatedCode(string $stateIncorporatedCode): void
  {
    $this->stateIncorporatedCode = $stateIncorporatedCode;
  }

}