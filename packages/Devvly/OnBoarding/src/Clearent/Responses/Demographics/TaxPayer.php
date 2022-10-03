<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\TaxPayerAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class TaxPayer extends Response
{
  use TaxPayerAttributes;

  public function __construct($data)
  {
    parent::__construct($data);
  }

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if ($underscore_keys) {
      unset($data['tin_type_i_d']);
      $data['tin_type_id'] = $this->tinTypeID;
    }
    return $data;
  }
}