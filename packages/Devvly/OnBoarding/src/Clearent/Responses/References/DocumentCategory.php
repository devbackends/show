<?php


namespace Devvly\OnBoarding\Clearent\Responses\References;


use Devvly\OnBoarding\Clearent\Responses\Response;

class DocumentCategory extends Response
{
  /** @var int */
  protected $documentCategoryID;

  /** @var string */
  protected $documentCategoryName;

  /** @var string */
  protected $documentCategoryDesc;

  /**
   * @return int
   */
  public function getDocumentCategoryID()
  {
    return $this->documentCategoryID;
  }

  /**
   * @return string
   */
  public function getDocumentCategoryName()
  {
    return $this->documentCategoryName;
  }

  /**
   * @return string
   */
  public function getDocumentCategoryDesc()
  {
    return $this->documentCategoryDesc;
  }
}