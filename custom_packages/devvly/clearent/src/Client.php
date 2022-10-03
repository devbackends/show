<?php

namespace Devvly\Clearent;

use Carbon\Carbon;
use Devvly\Clearent\Models\Response;
use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;

/**
 * Class Client
 */
class Client
{

  protected static $apiVersion = 'v2';

  protected static $apiNameSpace = "rest";

  /** @var mixed */
  protected $transaction;

  /** @var string: the api base url */
  protected $clearentApiUrl;

  /** @var string: api private key */
  protected $clearentApiPrivateKey;

  /** @var string: api public key */
  protected $clearentApiPublicKey;

  /** @var \Psr\Log\LoggerInterface: the logging service */
  protected $logger;

  /** @var string */
  private $clearentSoftwareType;

  /** @var string */
  private $clearentSoftwareVersion;

  /**
   * ClearentAPI constructor.
   *
   * @param  string  $clearentApiUrl
   * @param  string  $clearentApiPrivateKey
   * @param  string  $clearentApiPublicKey
   * @param  string  $clearentSoftwareType
   * @param  string  $clearentSoftwareVersion
   * @param  LoggerInterface  $logger
   */
  public function __construct(
      $clearentApiUrl,
      $clearentApiPrivateKey,
      $clearentApiPublicKey,
      $clearentSoftwareType,
      $clearentSoftwareVersion,
      LoggerInterface $logger
  ) {
    $this->clearentApiUrl = $clearentApiUrl;
    $this->clearentApiPrivateKey = $clearentApiPrivateKey;
    $this->clearentApiPublicKey = $clearentApiPublicKey;
    $this->logger = $logger;
    $this->clearentSoftwareType = $clearentSoftwareType;
    $this->clearentSoftwareVersion = $clearentSoftwareVersion;
  }

  /**
   * @return string
   */
  public function getClearentApiUrl(): string
  {
    return $this->clearentApiUrl;
  }

  /**
   * @return string
   */
  public function getClearentSoftwareType(): string
  {
    return $this->clearentSoftwareType;
  }

  /**
   * @return string
   */
  public function getClearentSoftwareVersion(): string
  {
    return $this->clearentSoftwareVersion;
  }

  /**
   * Gets Clearent api settings.
   *
   * @return array
   */
  public function getSettings()
  {
    return [
        'url' => $this->clearentApiUrl,
        'public_key' => $this->clearentApiPublicKey,
    ];
  }

  /**
   * Gets the current date
   *
   * @return \Carbon\Carbon
   */
  public function getCurrentDate()
  {
    $startDate = Carbon::now(env('APP_TIMEZONE', 'America/Chicago'));

    return $startDate;
  }

  /**
   * Performs a get request.
   *
   * @param $url
   * @param $headers
   *
   * @return array|\Exception|mixed|string|\Throwable|null
   */
  public function get(string $url, $headers = []){
    return $this->request($url,null, 'GET', $headers);
  }

  /**
   * Performs a post request.
   *
   * @param $url
   * @param $body
   * @param $headers
   *
   * @return array|\Exception|mixed|string|\Throwable|null
   */
  public function post(string $url, $body, $headers = []){
    return $this->request($url,$body, 'POST', $headers);
  }

  /**
   * Performs a put request.
   *
   * @param $url
   * @param $body
   * @param $headers
   *
   * @return array|\Exception|mixed|string|\Throwable|null
   */
  public function put(string $url, $body, $headers = []){
    return $this->request($url,$body, 'PUT', $headers);
  }

  /**
   * Performs a delete request.
   *
   * @param $url
   * @param $headers
   *
   * @return array|\Exception|mixed|string|\Throwable|null
   */
  public function delete(string $url, $headers = []){
    return $this->request($url,null, 'DELETE', $headers);
  }

  /**
   *
   * @param $url
   * @param $body
   * @param  string  $method
   * @param  array  $headers
   *
   * @return array|\Exception|mixed|string|\Throwable|null
   */
  public function request($url, $body, $method = "POST", $headers = [])
  {
    $url = $this->clearentApiUrl .'/'. self::$apiNameSpace .'/'. self::$apiVersion .$url;
    $_headers = [
        "Content-Type" => "application/json",
        "Accept" => "application/json",
        "api-key" => $this->clearentApiPrivateKey,
    ];
    $_headers = array_merge($_headers, $headers);
    $op = [
        "headers" => $_headers,
        "json" => $body,
    ];
    try {
      $httpClient = new BaseClient();
      $response = $httpClient->request($method, $url, $op);
      $data = json_decode($response->getBody()->getContents(), true);

      return $data;
    } catch (\Throwable $e) {
      if ($e instanceof ClientException) {
        $headers = $e->getResponse()->getHeaders();
        if (strpos($headers['Content-Type'][0], 'application/json') !== false) {
          $content = json_decode($e->getResponse()->getBody()->getContents(), true);
        } else {
          $content = $e->getMessage();
        }
      } else {
        $content = $e->getMessage();
      }
      $message = "Failed to connect to Clearent API";
      if ($content) {
        $this->logger->error($message, [$e->getCode(), $content]);

        return $content;
      } else {
        $this->logger->error($message, [$e]);
      }

      return $e;
    }
  }

  public function generateResponse($result): Response
  {
    if (is_array($result) && isset($result['status'])) {
      $result['status'] = strtolower($result['status']);
    }
    $default = [
        'code' => 0,
        'status' => 'failed',
        'exchange-id' => null,
        'payload' => [
            'payloadType' => 'error',
            'error' => [
                'error-message' => null,
            ],
        ],
    ];
    $type = gettype($result);
    switch ($type) {
      case "array":
        return new Response($result);
      case "string":
        $default['payload']['error']['error-message'] = $result;

        return new Response($default);
      default:
        $default['payload']['error']['error-message'] = "Couldn't instantiate response";

        return new Response($default);
    }
  }
}