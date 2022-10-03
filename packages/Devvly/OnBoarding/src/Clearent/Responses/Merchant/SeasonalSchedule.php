<?php


namespace Devvly\OnBoarding\Clearent\Responses\Merchant;


use Devvly\OnBoarding\Clearent\Models\Merchant\seasonalScheduleAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class SeasonalSchedule extends Response
{

  use seasonalScheduleAttributes;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->setAttributes($data);
  }

  public function toArray($underscore_keys = false)
  {
    $keys = get_object_vars($this);
    $data = [];
    foreach ($keys as $name => $value) {
      if($value){
        $data[] = $name;
      }
    }
    return $data;
  }

  protected function setAttributes($data)
  {
    $this->january = $data['january'];
    $this->february = $data['february'];
    $this->march = $data['march'];
    $this->april = $data['april'];
    $this->may = $data['may'];
    $this->june = $data['june'];
    $this->july = $data['july'];
    $this->august = $data['august'];
    $this->september = $data['september'];
    $this->october = $data['october'];
    $this->november = $data['november'];
    $this->december = $data['december'];
  }
}