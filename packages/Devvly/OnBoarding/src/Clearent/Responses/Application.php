<?php


namespace Devvly\OnBoarding\Clearent\Responses;


class Application extends Response
{
  protected $merchantNumber;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->merchantNumber = $data['merchantNumber'];
  }

  /**
   * @return mixed
   */
  public function getMerchantNumber()
  {
    return $this->merchantNumber;
  }


}