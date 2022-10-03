<?php


namespace Devvly\Clearent\Models;


class Link
{

  /**
   * @var string
   */
  protected $rel;

  /**
   * @var string|null
   */
  protected $href;

  /**
   * @var string|null
   */
  protected $id;

  /**
   * Link constructor.
   *
   * @param  array  $data
   */
  public function __construct(array $data)
  {
    $this->rel = $data['rel'];
    if (isset($data['href'])) {
      $this->href = $data['href'];
    }
    if (isset($data['id'])) {
      $this->id = $data['id'];
    }
  }

  /**
   * @return string
   */
  public function getRel(): string
  {
    return $this->rel;
  }

  /**
   * @return string
   */
  public function getHref(): string
  {
    return $this->href;
  }

  /**
   * @return string|null
   */
  public function getId(): ?string
  {
    return $this->id;
  }


}