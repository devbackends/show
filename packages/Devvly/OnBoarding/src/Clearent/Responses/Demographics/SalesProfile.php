<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\SalesProfileAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class SalesProfile extends Response
{
  use SalesProfileAttributes;

  public function toArray($underscore_keys = false)
  {
    $data = parent::toArray($underscore_keys);
    if($underscore_keys){
      unset($data['is_e_commerce']);
      $data['is_ecommerce'] = $this->isECommerce;
      unset($data['future_delivery_type_i_d']);
      $data['future_delivery_type_id'] = $this->futureDeliveryTypeID;
      unset($data['amex_m_i_d']);
      $data['amex_mid'] = $this->amexMID;
    }
    return $data;
  }
}