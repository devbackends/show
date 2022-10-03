<?php


namespace Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Clearent\Responses\Demographics\TermsAndConditions as Collection;
use Devvly\OnBoarding\Clearent\Responses\Demographics\TermsAndConditionsTypes;
use Devvly\OnBoarding\Clearent\Responses\Demographics\TermsAndConditionsType;
class TermsAndConditions extends IResource
{
  const path = "/demographics/v1.0/TermsAndConditions";

  /**
   * @return TermsAndConditionsTypes
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function getTypes(){
    $url = self::path . "/types";
    $result = $this->client->get($url);
    return new TermsAndConditionsTypes($result);
  }

  /**
   * @param $number
   *
   * @return Collection
   * @throws ClearentException
   */
  public function getMerchantTerms($number)
  {
    $typesResult = $this->getTypes();
    /** @var TermsAndConditionsType[] $types */
    $types = $typesResult->getContent();
    $url = self::path . "/" . $number;
    $headers = [
        'MerchantID' => $number,
    ];
    $result = $this->client->get($url, $headers);
    foreach ($result['content'] as $key => $term) {
      foreach ($types as $type) {
        if($term['typeID']  === $type->getTermsAndConditionsTypeID()){
          $result['content'][$key]['description'] = $type->getDescription();
        }
      }
    }
    $termsCollection = new Collection($result);
    return $termsCollection;
  }
}