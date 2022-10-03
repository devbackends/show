<?php

namespace Devvly\OnBoarding\Clearent;

use Carbon\Carbon;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;

/**
 * Class Client
 */
class Client
{

  protected $apiUrl = 'https://boarding-sb.clearent.net';
  protected $apiPrivateKey = "9af6f4f3-81a3-4eb4-81cd-e738ae7e7ffd";
  public $apiHierarchyKey = "6588000001308956";
  protected $apiNameSpace = "api";

  /** @var \Psr\Log\LoggerInterface */
  protected $logger;

  /**
   * ClearentAPI constructor.
   *
   * @param  LoggerInterface  $logger
   */
  public function __construct(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }


  /**
   * Gets Clearent api settings.
   *
   * @return array
   */
  public function getSettings()
  {
    return [

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
   * @return array
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function get($url, $headers = []){
    return $this->request($url,null, 'GET', $headers);
  }

  /**
   * Performs a post request.
   *
   * @param $url
   * @param $body
   * @param $headers
   *
   * @return array
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function post($url, $body, $headers = []){
    return $this->request($url,$body, 'POST', $headers);
  }

  /**
   * Performs a put request.
   *
   * @param $url
   * @param $body
   * @param $headers
   *
   * @return array
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function put($url, $body, $headers = []){
    return $this->request($url,$body, 'PUT', $headers);
  }

  /**
   * Performs a delete request.
   *
   * @param $url
   * @param $headers
   *
   * @return array
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function delete($url, $headers = []){
    return $this->request($url,null, 'DELETE', $headers);
  }


  /**
   * @param $url
   * @param $body
   * @param  string  $method
   * @param  array  $headers
   * @param  bool $json
   *
   * @return mixed
   * @throws \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  public function request($url, $body, $method = "POST", $headers = [], $json = true)
  {
    $url = $this->apiUrl .'/'. $this->apiNameSpace . $url;
    $_headers = [
        "AccessKey" => $this->apiPrivateKey,
    ];
    $_headers = array_merge($_headers, $headers);
    if($json){
      $op = ["json" => $body];
    }else{
      $op = ["body" => $body,];
    }
    $op['headers'] = $_headers;
    $httpClient = new BaseClient();
    try {
      $response = $httpClient->request($method, $url, $op);
      $data = json_decode($response->getBody()->getContents(), true);
    } catch (\Exception $e) {
      throw $this->generateException($e);
    }
    return $data;
  }

  /**
   * @param  \Exception $e
   *
   * @return \Devvly\OnBoarding\Clearent\Responses\ClearentException
   */
  protected function generateException($e)
  {
    if ($e instanceof ClientException || method_exists($e,'getResponse')) {
      $headers = $e->getResponse()->getHeaders();
      if (isset($headers['Content-Type']) && strpos($headers['Content-Type'][0], 'json') !== false) {
        $content = json_decode($e->getResponse()->getBody()->getContents(), true);
      }
      else {
        $content = $e->getMessage();
      }
    }
    else {
      $content = $e->getMessage();
    }
    $message = "Failed to connect to Clearent API";
    $this->logger->error($message, [$e->getCode(), $content]);
    return new ClearentException($content, $e->getCode());
  }
}