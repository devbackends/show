<?php


namespace Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Client;

abstract class ICrudResource
{

  /** @var Client  */
  protected $client;

  /**
   * IResource constructor.
   *
   * @param    $client
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * @return mixed
   */
  protected abstract function getResponseClass();

  protected function generateResponse($data){
    if(is_array($data)){
      $model = $this->getResponseClass();
      return new $model($data);
    }
    return $data;
  }

  /**
   * Finds a resource item with id
   *
   * @param string id
   *
   * @return mixed
   */
  public abstract function get($id);

  /**
   * Gets all items.
   *
   * @return mixed
   */
  public abstract function all();

  /**
   * Creates a resource.
   *
   * @param mixed $options
   *
   * @return mixed
   */
  public abstract function create($options);

  /**
   * Updates a resource.
   *
   * @param string $id
   * @param mixed $options
   *
   * @return mixed
   */
  public abstract function update($id, $options);

  /**
   * Deletes a resource.
   *
   * @param  string  $id
   *
   * @return mixed
   */
  public abstract function delete($id);
}