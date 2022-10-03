<?php


namespace Devvly\OnBoarding\Clearent\Responses\Equipment;


use Devvly\OnBoarding\Clearent\Responses\Response;

class Sort extends Response
{
  /** @var bool */
  protected $sorted;

  /** @var bool */
  protected $unsorted;

  /** @var bool */
  protected $empty;

  /**
   * @return bool
   */
  public function isSorted(): bool
  {
    return $this->sorted;
  }

  /**
   * @return bool
   */
  public function isUnsorted(): bool
  {
    return $this->unsorted;
  }

  /**
   * @return bool
   */
  public function isEmpty(): bool
  {
    return $this->empty;
  }
}