<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;

class VoidedCheck extends Document
{

  /** @var int */
  protected $bankAccountId;

  /**
   * @return int
   */
  public function getBankAccountId(): int
  {
    return $this->bankAccountId;
  }

  /**
   * @param  int  $bankAccountId
   */
  public function setBankAccountId(int $bankAccountId): void
  {
    $this->bankAccountId = $bankAccountId;
  }
}