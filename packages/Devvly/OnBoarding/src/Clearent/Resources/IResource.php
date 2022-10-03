<?php


namespace Devvly\OnBoarding\Clearent\Resources;


use Devvly\OnBoarding\Clearent\Client;

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
}