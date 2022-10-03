<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


class CompanyType
{
  /** @var int */
  protected $id;

  /** @var string */
  protected $description;

  public function __construct($data)
  {
    $this->id = $data['id'];
    $this->description = $data['description'];
  }

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }
}