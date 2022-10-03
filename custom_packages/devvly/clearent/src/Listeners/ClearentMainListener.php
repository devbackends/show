<?php


namespace Devvly\Clearent\Listeners;


use Devvly\Clearent\Client;
use Devvly\Clearent\Models\Response;

class ClearentMainListener
{

  /** @var Client */
  protected $client;

  /**
   * SubscriptionManager constructor.
   *
   * @param  Client  $client
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function run($name, $data): void
  {
    $stop = null;
    // 1. get the payload
    // 2. if transaction, activate the subscription.
    // if the event name is not clearent.hooks then exit:
    if($name !== 'clearent.hooks'){
      return;
    }

    $response = new Response($data);
    $payload = $response->getPayload();
    /** @var BaseListener $listener */
    $listener = null;
    switch ($payload->getPayloadType()){
      case "transaction":
        $listener = new Transactions($this->client);
        break;
      default:
        //
        break;
    }
    if($listener){
      $listener->run($payload);
    }
  }
}