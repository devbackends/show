<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Responses\Collection;

class TermsAndConditions extends Collection
{
  public function getType(): string
  {
    return TermsAndCondition::class;
  }
}