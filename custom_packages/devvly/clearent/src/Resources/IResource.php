<?php


namespace Devvly\Clearent\Resources;


use Devvly\Clearent\Client;
use Devvly\Clearent\Models\Payload;

abstract class IResource
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
   * Finds a resource item with id
   *
   * @param string id
   *
   * @return Payload
   */
  public abstract function get(string $id);

  /**
   * Gets all items.
   *
   * @return Payload
   */
  public abstract function all();

  /**
   * Creates a resource.
   *
   * @param mixed $options
   *
   * @return Payload
   */
  public abstract function create($options);

  /**
   * Updates a resource.
   *
   * @param string $id
   * @param mixed $options
   *
   * @return Payload
   */
  public abstract function update(string $id, $options);

  /**
   * Deletes a resource.
   *
   * @param  string  $id
   *
   * @return Payload
   */
  public abstract function delete(string $id);
}