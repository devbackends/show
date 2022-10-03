<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\ContactAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class Contact extends Response
{
  use ContactAttributes;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->setAttributes($data);
  }

  protected function setAttributes($data)
  {
    $keys = get_class_vars(self::class);
    unset($keys['metadata']);
    foreach ($keys as $key => $val) {
      if(isset($data[$key])){
        $this->{$key} = $data[$key];
      }
    }
    if(isset($data['address'])){
      $this->address = new Address($data['address']);
    }
  }
}