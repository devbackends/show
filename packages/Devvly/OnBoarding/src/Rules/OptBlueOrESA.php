<?php


namespace Devvly\OnBoarding\Rules;


use Illuminate\Contracts\Validation\ImplicitRule;

class OptBlueOrESA implements ImplicitRule
{

  public function passes($attribute, $value)
  {
    if (in_array(7, $value) && in_array(1, $value)) {
      return false;
    }
    return true;
  }

  public function message()
  {
    return "You can't select both American Express OptBlue and American Express ESA, Please select one or the other";
  }

}