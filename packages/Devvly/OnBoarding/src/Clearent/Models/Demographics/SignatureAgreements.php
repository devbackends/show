<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Collection;

class SignatureAgreements extends Collection
{

  /**
   * {@inheritDoc}
   */
  public function getType(): string
  {
    return SignatureAgreement::class;
  }
}