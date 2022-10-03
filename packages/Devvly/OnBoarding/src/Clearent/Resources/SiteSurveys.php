<?php


namespace Devvly\OnBoarding\Clearent\Resources;



use Devvly\OnBoarding\Clearent\Models\Demographics\SiteSurvey as SiteSurveyModel;
use Devvly\OnBoarding\Clearent\Responses\Demographics\SiteSurvey;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class SiteSurveys extends IResource
{
  const path = "/demographics/v1.0/SiteSurveys";

  /**
   * @param  int  $merchantNumber
   *
   * @return SiteSurvey
   * @throws ClearentException
   */
  public function get($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    return new SiteSurvey($result);
  }

  /**
   * @param  int  $merchantNumber
   * @param  SiteSurveyModel  $options
   *
   * @return SiteSurvey
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
    return new SiteSurvey($result);
  }

}