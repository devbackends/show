<?php


namespace Devvly\Clearent\Resources;


use Devvly\Clearent\Models\PlanOptions;
use Devvly\Clearent\Models\Response;
use Devvly\Clearent\Models\SaleOptions;
use Devvly\Clearent\Models\Transaction;
use Devvly\Clearent\Models\Error;

class Transactions extends IResource
{
  const path = "/transactions";

  /**
   * Gets a transaction.
   *
   * @param  string  $id
   *
   * @return Transaction|Error
   */
  public function get(string $id)
  {
    $url = self::path . '/' . $id;
    $result = $this->client->request($url, null, 'GET');
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Gets all transactions.
   *
   * @return Transactions|Error
   */
  public function all()
  {
    $result = $this->client->request(self::path, null, "GET");
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * @param  \Devvly\Clearent\Models\SaleOptions  $options
   *
   * @return Transaction|Error
   */
  public function sale(SaleOptions $options)
  {
    $body = [
        "type" => "SALE",
        "amount" => $options->getAmount(),
        "software-type" => $this->client->getClearentSoftwareType(),
        "software-type-version" => $this->client->getClearentSoftwareVersion(),
    ];
    $url = '/mobile/transactions/sale';
    $headers = [];
    if ($options->getCard()) {
      $body['card'] = $options->getCard();
      $body['exp-date'] = $options->getExpDate();
      $url = '/transactions/sale';
    } else {
      if ($options->createCardToken()) {
          $body['create-token'] = true;
          $body['token-description'] = 'to use for other orders.';
          $body['card-type'] = $options->getCardType();
      }
      $body['billing'] = [
          'zip' => $options->getZipCode(),
      ];
      $headers = [
          'mobilejwt' => $options->getJwtToken(),
      ];
    }
    $result = $this->client->request($url, $body, 'POST', $headers);

    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * @param  PlanOptions  $options
   *
   * @return Response
   */
  public function create($options)
  {
    return null;
  }

  public function update(string $id, $options)
  {
    return null;
  }

  public function delete(string $id)
  {
    return null;
  }


}