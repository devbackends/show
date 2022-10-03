<?php


namespace Devvly\OnBoarding\Clearent\Resources;



use Devvly\OnBoarding\Clearent\Models\Demographics\BusinessContact as BusinessContactModel;
use Devvly\OnBoarding\Clearent\Responses\Demographics\BusinessContact;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class BusinessContacts extends IResource
{
  const path = "/demographics/v1.0/BusinessContacts";

  /**
   * @param  int  $id
   * @param  int  $merchantNumber
   *
   * @return BusinessContact
   * @throws ClearentException
   */
  public function getItem($id, $merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber . "/" . $id;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    return new BusinessContact($result);
  }

  /**
   * @param  int  $merchantNumber
   *
   * @return BusinessContact[]
   * @throws ClearentException
   */
  public function get($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    $contacts = [];
    foreach ($result['content'] as $item) {
      $contacts[] = new BusinessContact($item);
    }
    return $contacts;
  }

  /**
   * @param  int  $merchantNumber
   * @param  BusinessContactModel  $options
   *
   * @return BusinessContact
   * @throws ClearentException
   */
  public function create($merchantNumber, $options)
  {
    $url = self::path . "/" . $merchantNumber;
    $body = $options->toArray();
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->post($url, $body, $headers);
    return new BusinessContact($result);
  }

  /**
   * @param  int  $id
   * @param  int  $merchantNumber
   * @param  BusinessContactModel  $options
   *
   * @return BusinessContact
   * @throws ClearentException
   */
  public function update($id, $merchantNumber, $options)
  {
    $url = self::path . "/" . $merchantNumber . "/" . $id;
    $body = $options->toArray();
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->put($url, $body, $headers);
    return new BusinessContact($result);
  }

}