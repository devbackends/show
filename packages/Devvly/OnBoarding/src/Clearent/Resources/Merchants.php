<?php


namespace Devvly\OnBoarding\Clearent\Resources;



use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Merchant\Merchant as MerchantModel;
use Devvly\OnBoarding\Clearent\Responses\Merchant\Merchant;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class Merchants extends ICrudResource
{
  const path = "/demographics/v1.0/Merchants";

  public function __construct(Client $client)
  {
    parent::__construct($client);
  }

  protected function getResponseClass()
  {
    return Merchant::class;
  }

  /**
   * @param $id
   *
   * @return Merchant
   * @throws ClearentException
   */
  public function get($id)
  {
    $url = self::path . '/' . $id;
    $body = [];
    $body['hierarchyNodeKey'] = $this->client->apiHierarchyKey;
    $headers = [
        'MerchantID' => $id
    ];
    $result = $this->client->get($url, $headers);
    return $this->generateResponse($result);
  }

  public function all()
  {
    // TODO: Implement all() method.
  }

  /**
   * @param  MerchantModel  $options
   *
   * @return Merchant
   * @throws ClearentException
   */
  public function createOrUpdate($options)
  {
    $url = self::path;
    $create = true;
    try {
      $this->get($options->getMerchantNumber());
      $create = false;
      $url = self::path .'/'.  $options->getMerchantNumber();
    } catch (ClearentException $e) {
      if($e->getCode() !== 404){
        throw $e;
      }
    }
    $body = $options->toArray();
    $body['hierarchyNodeKey'] = $this->client->apiHierarchyKey;
    $headers = [
        'MerchantID' => $options->getMerchantNumber(),
    ];
    if ($create) {
      $result = $this->client->post($url, $body, $headers);
    }
    else {
      $result = $this->client->put($url, $body, $headers);
    }
    return $this->generateResponse($result);
  }

  public function create($options)
  {
    // TODO: Implement create() method.
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