<?php


namespace Devvly\OnBoarding\Clearent\Responses\Merchant;



use Devvly\OnBoarding\Clearent\Responses\Response;

class Link extends Response
{
  /** @var string  */
  protected $id;

  /** @var string */
  protected $href;

  public function __construct($data)
  {
    parent::__construct($data);
    $this->id = $data['id'];
    $this->href = $data['href'];
  }

  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getHref()
  {
    return $this->href;
  }
}