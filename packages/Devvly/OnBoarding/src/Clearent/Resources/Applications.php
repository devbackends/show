<?php


namespace Devvly\OnBoarding\Clearent\Resources;

use Devvly\OnBoarding\Clearent\Models\Application as ApplicationModel;
use Devvly\OnBoarding\Clearent\Responses\Application;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class Applications extends ICrudResource
{

  const path = "/BoardingManagement/v1.0/Applications";

  protected function getResponseClass()
  {
    return Application::class;
  }

  public function get($id)
  {
    // TODO: Implement get() method.
  }

  public function all()
  {
    // TODO: Implement all() method.
  }

  /**
   * Create an application for a customer (merchant) to do business.
   *
   * @param  ApplicationModel  $options
   *
   * @return Application
   * @throws ClearentException
   */
  public function create($options)
  {
    $url = self::path . "/Create";
    $body = [
        'dbaName' => $options->getDbaName(),
        'hierarchyNodeKey' => $this->client->apiHierarchyKey,
    ];
    $headers = [
        'MerchantID' => $this->client->apiHierarchyKey,
    ];
    $result = $this->client->post($url, $body, $headers);
    return $this->generateResponse($result);
  }

  /**
   * @param $merchantNumber
   *
   * @throws ClearentException
   */
  public function submit($merchantNumber)
  {
    $url = self::path . '/' . $merchantNumber . '/submit';
    $headers = [
        'MerchantID' => $merchantNumber,
    ];
    $result = $this->client->post($url, [], $headers);
    $stop = null;
  }

  public function update($id, $options)
  {
    // TODO: Implement update() method.
  }

  public function delete($id)
  {
    // TODO: Implement delete() method.
  }

}