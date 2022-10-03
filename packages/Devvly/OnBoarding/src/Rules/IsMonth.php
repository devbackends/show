<?php


namespace Devvly\OnBoarding\Rules;


use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class IsMonth implements Rule
{

  public function passes($attribute, $value)
  {
    $months = [
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
    ];
    return in_array($value, $months);
  }

  public function message()
  {
    return "Month is unknown.";
  }

}