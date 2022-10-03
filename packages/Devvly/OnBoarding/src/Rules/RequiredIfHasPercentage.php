<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\ImplicitRule;

class RequiredIfHasPercentage implements ImplicitRule
{
  protected $percentage;
  public function __construct($percentage)
  {
    $this->percentage = $percentage;
  }

  public function passes($attribute, $value)
  {
    if($this->percentage&& !$value){
      return false;
    }
    return true;
  }

  public function message()
  {
    return "The :attribute is required when Future Delivery Percentage is more than 0";
  }

}