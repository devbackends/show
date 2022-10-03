<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\ImplicitRule;

class RequiredIfAmex implements ImplicitRule
{
  protected $brands;
  public function __construct($brands)
  {
    $this->brands = $brands;
  }

  public function passes($attribute, $value)
  {
    if(in_array(7, $this->brands) && $value){
      return true;
    }
    if(!in_array(7, $this->brands)){
      return true;
    }
    return false;
  }

  public function message()
  {
    return "The :attribute is required when American Express ESA is selected";
  }

}