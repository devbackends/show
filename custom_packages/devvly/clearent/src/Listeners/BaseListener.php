<?php


namespace Devvly\Clearent\Listeners;


use Devvly\Clearent\Client;

abstract class BaseListener
{
  /** @var Client */
  protected $client;

  /**
   * BaseListener constructor.
   *
   * @param  Client  $client
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Runs the listener
   *
   * @param  mixed  $data
   */
  public abstract function run($data);
}