<?php


namespace Devvly\Clearent\Listeners;
use Devvly\Clearent\Models\Transaction as TransactionModel;
use Devvly\Clearent\Models\Token;
class Transactions extends BaseListener
{

  /**
   * Runs the listener
   * @param  TransactionModel  $data
   */
  public function run($data): void
  {
    $links = $data->getLinks();
    $tokenLink = null;
    foreach ($links as $link) {
      if($link->getRel() === 'token'){
        $tokenLink = $link;
      }
    }
    if(!$tokenLink){
      return;
    }
    $url = '/tokens/' . $tokenLink->getId();
    $result = $this->client->get($url);
    $response = $this->client->generateResponse($result);
    /** @var Token $token */
    $token = $response->getPayload();
    $payload = null;
    $stop = null;
  }
}