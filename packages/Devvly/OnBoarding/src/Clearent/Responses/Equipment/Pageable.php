<?php


namespace Devvly\OnBoarding\Clearent\Responses\Equipment;


use Devvly\OnBoarding\Clearent\Responses\Response;

class Pageable extends Response
{
  /** @var Sort */
  protected $sort;

  /** @var int */
  protected $pageSize;

  /** @var int */
  protected $pageNumber;

  /** @var int */
  protected $offset;

  /** @var bool */
  protected $unpaged;

  /** @var bool */
  protected $paged;

  /**
   * @return Sort
   */
  public function getSort(): Sort
  {
    return $this->sort;
  }

  /**
   * @param  Sort|array $sort
   */
  protected function setSort($sort): void
  {
    $this->sort = new Sort($sort);
  }

  /**
   * @return int
   */
  public function getPageSize(): int
  {
    return $this->pageSize;
  }

  /**
   * @return int
   */
  public function getPageNumber(): int
  {
    return $this->pageNumber;
  }

  /**
   * @return int
   */
  public function getOffset(): int
  {
    return $this->offset;
  }

  /**
   * @return bool
   */
  public function isUnpaged(): bool
  {
    return $this->unpaged;
  }

  /**
   * @return bool
   */
  public function isPaged(): bool
  {
    return $this->paged;
  }

}