<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Models\Demographics\SiteSurveyAttributes;
use Devvly\OnBoarding\Clearent\Responses\Response;

class SiteSurvey extends Response
{
  use SiteSurveyAttributes;

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
  }
}