<?php


namespace Devvly\OnBoarding\Clearent\Listeners;


use Devvly\OnBoarding\Clearent\Client;

abstract class BaseListener
{
  /** @var Client */
  protected $client;

  /**
   * BaseListener constructor.
   *
   * @param  Client  $client
   */
  public function __construct($client)
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