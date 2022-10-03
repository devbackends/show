<?php


namespace Devvly\OnBoarding\Clearent\Resources;

use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Responses\Demographics\Address;
use Devvly\OnBoarding\Clearent\Models\Demographics\Address as AddressModel;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class PhysicalAddresses extends IResource
{

  const path = "/demographics/v1.0/MerchantPhysicalAddresses";

  public function __construct(Client $client)
  {
    parent::__construct($client);
  }

  /**
   * @param $merchantNumber
   *
   * @return Address
   * @throws ClearentException
   */
  public function get($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber,
    ];
    $result = $this->client->get($url, $headers);
    return new Address($result);
  }

  /**
   * @param $merchantNumber
   * @param AddressModel $options
   *
   * @return Address
   * @throws ClearentException
   */
  public function update($merchantNumber, $options)
  {
    $url = self::path . "/" . $merchantNumber;
    $body = $options->toArray();
    $headers = [
        'MerchantID' => $merchantNumber,
    ];
    $result = $this->client->put($url, $body, $headers);
    return new Address($result);
  }


}