<?php


namespace Devvly\OnBoarding\Clearent\Resources;



use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Demographics\Document;
use Devvly\OnBoarding\Clearent\Models\Demographics\SalesProfile as SalesProfileModel;
use Devvly\OnBoarding\Clearent\Responses\Demographics\SalesProfile;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
class SalesProfiles extends IResource
{
  const path = "/demographics/v2.0/SalesProfiles";

  /** @var Resources */
  protected $resources;

  public function __construct(Client $client, $resources)
  {
    parent::__construct($client);
    $this->resources = $resources;
  }

  /**
   * @param  int  $merchantNumber
   *
   * @return SalesProfile
   * @throws ClearentException
   */
  public function get($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    return new SalesProfile($result);
  }

  /**
   * @param  int  $merchantNumber
   * @param  SalesProfileModel  $options
   *
   * @return SalesProfile
   * @throws ClearentException
   */
  public function update($merchantNumber, $options)
  {
    $options->setPreviousProcessorId(null);
    // upload license if sells firearms:
    if ($options->isSellsFirearms()) {
      $path = $options->getFirearmsLicenseDocumentPath();
      $chunks = explode('.',$path);
      $fileName = 'firearm_license_' . $merchantNumber . '.' . $chunks[count($chunks) -1];
      $document = new Document();
      $document->setFileName($fileName);
      $document->setFilePath($path);
      $document->setMerchantNumber($merchantNumber);
      $document->setCategory(16); // Federal Firearms License
      $this->resources->documents()->upload($document);
    }
    $url = self::path . "/" . $merchantNumber;
    $body = $options->toArray();
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->put($url, $body, $headers);
    return new SalesProfile($result);
  }

}