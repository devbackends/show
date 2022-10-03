<?php


namespace Devvly\Clearent\Resources;

use Devvly\Clearent\Client;

class Resources
{

  protected $resources;

  /**
   * Resources constructor.
   *
   * @param Client $client
   */
  public function __construct(Client $client)
  {
    $this->resources = [
        'plans' => new Plans($client),
        'customers' => new Customers($client),
        'transactions' => new Transactions($client),
        'tokens' => new Tokens($client),
    ];

  }

  /**
   * @return Plans
   */
  public function plans(): Plans
  {
    return $this->resources['plans'];
  }

  /**
   * @return Customers
   */
  public function customers(): Customers
  {
    return $this->resources['customers'];
  }

  /**
   * @return Transactions
   */
  public function transactions(): Transactions
  {
    return $this->resources['transactions'];
  }

  /**
   * @return Tokens
   */
  public function tokens(): Tokens
  {
    return $this->resources['tokens'];
  }
}