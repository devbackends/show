<?php


namespace Devvly\Clearent\Resources;

use Devvly\Clearent\Models\Token;
use Devvly\Clearent\Models\Error;

class Tokens extends IResource
{
  const path = "/tokens";

  /**
   * Gets a token.
   *
   * @param  string  $id
   *
   * @return Token|Error
   */
  public function get(string $id)
  {
    $url = self::path . '/' . $id;
    $result = $this->client->get($url);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Gets all tokens.
   *
   * @return Token|Error
   */
  public function all()
  {
    $result = $this->client->get(self::path);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Creates a token.
   *
   * @param  string $jwtToken
   *
   * @return Token|Error
   */
  public function create($jwtToken)
  {
    $url = '/mobile/transactions/auth';
    $headers = [
        'mobilejwt' => $jwtToken,
    ];
    $body = [
        "type" => "auth",
        "amount" => "0.00",
        "create-token" => true,
        "software-type" => $this->client->getClearentSoftwareType(),
        "software-type-version" => $this->client->getClearentSoftwareVersion(),
    ];
    $result = $this->client->post($url, $body, $headers);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

  /**
   * Updates a token.
   *
   * @param  string  $id
   * @param  mixed  $options
   *
   * @return Token|Error
   */
  public function update(string $id, $options)
  {
    return null;
  }

  /**
   * Deletes a token.
   *
   * @param  string  $id
   *
   * @return Token|Error
   */
  public function delete(string $id)
  {
    $url = self::path . '/' . $id;
    $result = $this->client->delete($url);
    $response = $this->client->generateResponse($result);
    return $response->getPayload();
  }

}