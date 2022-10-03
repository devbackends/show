<?php


namespace Devvly\OnBoarding\Clearent\Responses\Equipment;


use Devvly\OnBoarding\Clearent\Responses\Response;
class ProductsPagination extends Response
{
  /** @var Product[] */
  protected $content;

  /** @var Pageable */
  protected $pageable;

  /** @var int */
  protected $totalPages;

  /** @var int */
  protected $totalElements;

  /** @var bool */
  protected $last;

  /** @var Sort */
  protected $sort;

  /** @var int */
  protected $numberOfElements;

  /** @var bool */
  protected $first;

  /** @var int */
  protected $size;

  /** @var int */
  protected $number;

  /** @var bool */
  protected $empty;

  /**
   * @return Product[]
   */
  public function getContent(): array
  {
    return $this->content;
  }

  /**
   * @param  Product[]|array  $content
   */
  public function setContent(array $content): void
  {
    $this->content = [];
    foreach ($content as $item) {
      if(!$item instanceof Product){
        $item = new Product($item);
      }
      $this->content[] = $item;
    }
  }

  /**
   * @return Pageable
   */
  public function getPageable(): Pageable
  {
    return $this->pageable;
  }

  /**
   * @param  Pageable|array  $pageable
   */
  public function setPageable($pageable): void
  {
    if(!$pageable instanceof Pageable){
      $pageable = new Pageable($pageable);
    }
    $this->pageable = $pageable;
  }

  /**
   * @return int
   */
  public function getTotalPages(): int
  {
    return $this->totalPages;
  }

  /**
   * @return int
   */
  public function getTotalElements(): int
  {
    return $this->totalElements;
  }

  /**
   * @return bool
   */
  public function isLast(): bool
  {
    return $this->last;
  }

  /**
   * @return Sort
   */
  public function getSort(): Sort
  {
    return $this->sort;
  }

  /**
   * @param  array  $sort
   */
  protected function setSort($sort): void
  {
    $this->sort = new Sort($sort);
  }

  /**
   * @return int
   */
  public function getNumberOfElements(): int
  {
    return $this->numberOfElements;
  }

  /**
   * @return bool
   */
  public function isFirst(): bool
  {
    return $this->first;
  }

  /**
   * @return int
   */
  public function getSize(): int
  {
    return $this->size;
  }

  /**
   * @return int
   */
  public function getNumber(): int
  {
    return $this->number;
  }

  /**
   * @return bool
   */
  public function isEmpty(): bool
  {
    return $this->empty;
  }

}