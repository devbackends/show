<?php


namespace Devvly\OnBoarding\Clearent\Responses;

abstract class Collection extends Response
{

  /** @var Response[] */
  protected $content;

  /**
   * @return string The collection type
   */
  public abstract function getType(): string;


  /**
   * @param  Response[]  $content
   */
  protected function setContent(array $content): void
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

  /**
   * @return \Devvly\OnBoarding\Clearent\Responses\Response[]
   */
  public function getContent(): array
  {
    return $this->content;
  }
}