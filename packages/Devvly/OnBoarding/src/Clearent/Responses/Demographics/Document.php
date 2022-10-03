<?php


namespace Devvly\OnBoarding\Clearent\Responses\Demographics;


use Devvly\OnBoarding\Clearent\Responses\Response;

class Document extends Response
{
  /** @var string */
  protected $documentId;

  /**
   * @return string
   */
  public function getDocumentId(): string
  {
    return $this->documentId;
  }
}