<?php


namespace Devvly\OnBoarding\Clearent\Resources;

use Devvly\OnBoarding\Clearent\Models\Demographics\Document as DocumentModel;
use Devvly\OnBoarding\Clearent\Models\Demographics\VoidedCheck as Model;
use Devvly\OnBoarding\Clearent\Responses\Demographics\Document as DocumentResponse;

class Documents extends IResource
{

  const path = "/demographics/v1.0/Documents";

  /**
   * @param DocumentModel $document
   *
   * @return DocumentResponse
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function upload($document)
  {
    $number = $document->getMerchantNumber();
    $filename = $document->getFileName();
    $cat = $document->getCategory();
    $url = self::path . "/{$number}?fileName={$filename}&documentCategory={$cat}";
    $headers = [
        'MerchantID' => $number,
        'Content-Type' => 'application/json',
    ];
    $body = $document->getFileBytes();
    $res = $this->client->request($url, $body, 'POST', $headers, false);
    $doc = new DocumentResponse($res);
    return $doc;
  }

  /**
   * @param Model $document
   *
   * @return DocumentResponse
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function uploadVoidedCheck($document)
  {
    $number = $document->getMerchantNumber();
    $filename = $document->getFileName();
    $accountId  = $document->getBankAccountId();
    $url = self::path . "/{$number}/VoidedChecks?fileName={$filename}&bankAccountId={$accountId}";
    $headers = [
        'MerchantID' => $number,
        'Content-Type' => 'application/json',
    ];
    $body = $document->getFileBytes();
    $res = $this->client->request($url, $body, 'POST', $headers, false);
    $doc = new DocumentResponse($res);
    return $doc;
  }

}