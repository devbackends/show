<?php


namespace Devvly\OnBoarding\Clearent\Models;

abstract class Collection extends Model
{

  /** @var Model[] */
  protected $content;

  /**
   * @return string The collection type
   */
  public abstract function getType(): string;

  /**
   * @param  Model[]  $content
   */
  public function setContent(array $content): void
  {
    $class = $this->getType();
    $items = [];
    foreach ($content as $item) {
      if(!$item instanceof $class){
       $item = new $class($item);
      }
      $items[] = $item;
    }
    $this->content = $items;
  }
}