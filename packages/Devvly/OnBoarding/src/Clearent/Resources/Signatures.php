<?php


namespace Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Demographics\SignatureAgreement;
use Devvly\OnBoarding\Clearent\Models\Demographics\SignatureAgreements;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class Signatures extends IResource
{
  /** @var string */
  const path = '/demographics/v1.0/Signatures';

  /** @var Resources */
  protected $resources;

  public function __construct(Client $client, Resources $resources)
  {
    parent::__construct($client);
    $this->resources = $resources;
  }


  /**
   * Collects and signs the agreements
   * @param string $number
   * @param int $contactId
   * @param string $ipAddress
   *
   * @return SignatureAgreements
   * @throws ClearentException
   */
  protected function signAgreements($number, $contactId, $ipAddress)
  {
    $type = 2; // OnlineForm;
    $time = gmdate('Y-m-d\TH:i:s');
    $sections = $this->resources->references()->getSignatureSections();
    $agreements = [];
    foreach ($sections as $section) {
      $agreement = new SignatureAgreement();
      $agreement->setIpAddress($ipAddress);
      $agreement->setTimestamp($time);
      $agreement->setSignatureSourceTypeId($type);
      $agreement->setSignatureSectionTypeId($section->getSignatureSectionTypeId());
      $agreement->setSignerViewedLegalText(true);
      $agreement->setBusinessContactId($contactId);
      $agreements[] = $agreement->toArray();
    }
    $data = [
        'content' => $agreements,
    ];
    $headers = [
        'MerchantID' => $number,
    ];
    $url = self::path . '/' . $number;
    $res = $this->client->put($url, $data, $headers);
    return new SignatureAgreements($res);
  }
  /**
   * @param string $number The merchant number
   * @param int $businessContactId The signer contact id
   * @param string $ipAddress The signer IP Address
   *
   * @throws ClearentException
   */
  public function submitSignatures($number, $businessContactId, $ipAddress)
  {
    $this->signAgreements($number, $businessContactId, $ipAddress);
    $url = '/BoardingManagement/v1.0/Applications/' . $number . '/SubmitSignatures';
    $headers = [
        'MerchantID' => $number,
    ];
    $res = $this->client->post($url, [], $headers);
    $stop = null;
  }
}