<?php


namespace Devvly\Clearent\Resources;


use Devvly\Clearent\Models\CustomerOptions;
use Devvly\Clearent\Models\Response;
use Devvly\Clearent\Models\Token;
use Devvly\Clearent\Models\Customer;
use Devvly\Clearent\Models\Error;

class Customers extends IResource
{
  const path = "/customers";

  /**
   * Gets a customer.
   *
   * @param  string  $id
   *
   * @return Customer|Error
   */
  public function get(string $id)
  {
    $url = self::path . '/' . $id;
    $result = $this->client->get($url);
    $response =  $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Gets all customers.
   *
   * @return Customer|Error
   */
  public function all()
  {
    $result = $this->client->get(self::path);
    $response =  $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Creates a customer.
   *
   * @param  CustomerOptions  $options
   *
   * @return Customer|Error
   */
  public function create($options)
  {
    $body = [
        'first-name' => $options->getFirstName(),
        'last-name' => $options->getLastName(),
    ];
    $result = $this->client->post(self::path, $body);
    $response =  $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Updates a customer.
   *
   * @param  string  $id
   * @param  mixed  $options
   *
   * @return Customer|Error
   */
  public function update(string $id, $options)
  {
    $url = self::path . '/' . $id;
    $body = [
        'first-name' => $options->getFirstName(),
        'last-name' => $options->getLastName(),
    ];
    $result = $this->client->put($url, $body);
    $response =  $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Deletes a customer.
   *
   * @param  string  $id
   *
   * @return Customer|Error
   */
  public function delete(string $id)
  {
    $url = self::path . "/" . $id;
    $result = $this->client->delete($url);
    $response =  $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Attaches a card token to a customer.
   *
   * @param  Token  $token
   * @param  string  $customerKey
   *
   * @return Response
   */
  public function addTokenToCustomer(Token $token, string $customerKey)
  {
    $url = self::path .'/'. $customerKey . "/tokens";
    $body = [
        'card-type' => $token->getCardType(),
        'exp-date' => $token->getExpDate(),
        'last-four-digits' => $token->getLastFour(),
        'token-id' => $token->getTokenId(),
        'customer-key' => $customerKey,
    ];
    $res = $this->client->post($url, $body);

    return $this->client->generateResponse($res);
  }

}