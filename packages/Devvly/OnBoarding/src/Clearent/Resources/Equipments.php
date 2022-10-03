<?php


namespace Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Responses\Equipment\Products;

class Equipments extends IResource
{
  const path = "/equipment";

  /**
   * @param string $term
   *
   * @return Products
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function listProducts($term = null)
  {
    $url = self::path . '/products?size=10000';
    if($term){
      $url .= '?term=' . $term;
    }
    $result = $this->client->get($url);
    $products = new Products($result);
    return $products;
  }


  /**
   * @param string $productName
   *
   * @return array
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function getSurvey($productName)
  {
    $url = self::path . "/surveys/" . $productName;
    $result = $this->client->get($url);
    return $result;
  }

}