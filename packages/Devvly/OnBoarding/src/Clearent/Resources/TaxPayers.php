<?php


namespace Devvly\OnBoarding\Clearent\Resources;



use Devvly\OnBoarding\Clearent\Models\Demographics\TaxPayer as TaxPayerModel;
use Devvly\OnBoarding\Clearent\Responses\Demographics\TaxPayer;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class TaxPayers extends IResource
{
  const path = "/demographics/v2.0/Taxpayers";

  /**
   * @param  int  $merchantNumber
   *
   * @return TaxPayer
   * @throws ClearentException
   */
  public function get($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    return new TaxPayer($result);
  }

  /**
   * @param  int  $merchantNumber
   * @param  TaxPayerModel  $options
   *
   * @return TaxPayer
   * @throws ClearentException
   */
  public function update($merchantNumber, $options)
  {
    $url = self::path . "/" . $merchantNumber;
    $body = $options->toArray();
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->put($url, $body, $headers);
    return new TaxPayer($result);
  }

}