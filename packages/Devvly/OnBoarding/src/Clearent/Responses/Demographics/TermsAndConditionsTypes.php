<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Responses\Collection;

class TermsAndConditionsTypes extends Collection
{

  public function getType(): string
  {
    return TermsAndConditionsType::class;
  }

}