<?php

namespace Devvly\Subscription\Services\Clearent;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class ClearentAPI
 */
class ClearentApi
{
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
    public function sale(SaleOptions $options){
        $body = [
            "type" => "SALE",
            "amount" => $options->getAmount(),
            "software-type" => $this->clearentSoftwareType,
            "software-type-version" => $this->clearentSoftwareVersion,
        ];
        $url = '/rest/v2/mobile/transactions/sale';
        $headers = [];
        if($options->getCard()){
            $body['card'] = $options->getCard();
            $body['exp-date'] = $options->getExpDate();
            $url = '/rest/v2/transactions/sale';
        }else{
            $body['create-token'] = true;
            $body['token-description'] = 'to use for other orders.';
            $body['card-type'] = $options->getCardType();
            $headers = [
                'mobilejwt' => $options->getJwtToken(),
            ];
        }
        $result = $this->request($url, $body, 'POST', $headers);
        return $this->generateResponse($result);
    }
    /**
     * @param  PlanOptions  $options
     *
     * @return Response
     */
    public function createPlan(PlanOptions $options){
        $url = '/rest/v2/payment-plans';
        $body = [
            'customer-key' => $options->getCustomerKey(),
            'start-date' => $options->getStartDate()->format('Y-m-d'),
            'end-date' => $options->getEndDate()->addDay()->format('Y-m-d'),
            'payment-amount' => $options->getPaymentAmount(),
            'token-id' => $options->getTokenId(),
            'frequency' => $options->getFrequency(),
        ];
        $startDateObj = $options->getStartDate();
        switch ($options->getFrequency()) {
            case PlanOptions::PLAN_WEEKLY:
                $body['frequency-week'] = $startDateObj->week;
                break;
            case PlanOptions::PLAN_MONTHLY:
            case PlanOptions::PLAN_YEARLY:
                $body['frequency-month'] = $startDateObj->month;
                break;
        }
        $frequencyDay = null;
        $plan = $options->getFrequency();
        if ($plan === PlanOptions::PLAN_MONTHLY || $plan === PlanOptions::PLAN_YEARLY) {
            $frequencyDay = $startDateObj->day;
        } else {
            $frequencyDay = $startDateObj->dayOfWeek + 1;
        }
        $body['frequency-day'] = $frequencyDay;
        $result = $this->request($url, $body);
        return $this->generateResponse($result);
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
        $url = "/rest/v2/customers/${customerKey}/tokens";
        $body = [
            'card-type' => $token->getCardType(),
            'exp-date' => $token->getExpDate(),
            'last-four-digits' => $token->getLastFour(),
            'token-id' => $token->getTokenId(),
            'customer-key' => $customerKey,
        ];
        $res = $this->request($url, $body);
        return $this->generateResponse($res);
    }
    /**
     * @param  string  $key
     *
     * @return array|\Exception|mixed|string|\Throwable|null
     */
    public function getCustomer(string $key){
        $url = "/rest/v2/customers/${key}/tokens";
        // todo: return custom response class:
        return $this->request($url,null, "GET");
    }
    public function createCustomer($firstName, $lastName){
        $url = '/rest/v2/customers';
        $body = [
            'first-name' => $firstName,
            'last-name' => $lastName,
        ];
        // todo: return custom response class:
        $result = $this->request($url, $body);
        return $this->generateResponse($result);
    }
    /**
     * Creates a long-term token to use as a card
     *
     * @param  string  $jwtToken
     *
     * @return Response
     */
    public function createToken(string $jwtToken){
        $url = '/rest/v2/mobile/transactions/auth';
        $headers = [
            'mobilejwt' => $jwtToken,
        ];
        $body = [
            "type" => "auth",
            "amount" => "0.00",
            "create-token" => true,
            "software-type" => $this->clearentSoftwareType,
            "software-type-version" => $this->clearentSoftwareVersion,
        ];
        $result = $this->request($url, $body, 'POST', $headers);
        return $this->generateResponse($result);
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
    protected function request($url, $body, $method = "POST", $headers = [])
    {
        $url = $this->clearentApiUrl.$url;
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
            $httpClient = new Client();
            $response = $httpClient->request($method, $url, $op);
            $data = json_decode($response->getBody()->getContents(),TRUE);
            return $data;
        } catch (\Throwable $e) {
           if ( $e instanceof ClientException) {
              $headers = $e->getResponse()->getHeaders();
              if (strpos($headers['Content-Type'][0],'application/json') !== FALSE) {
                $content = json_decode($e->getResponse()->getBody()->getContents(),TRUE);
              }
              else {
                $content = $e->getMessage();
              }
            }
            else {
              $content = $e->getMessage();
            }
            $message = "Failed to connect to Clearent API";
            if ($content) {
                $this->logger->error($message, [$e]);
                return $content;
            }
            else {
              $this->logger->error($message, [$e]);
            }
            return $e;
        }
    }
    protected function generateResponse($result): Response
    {
        if(is_array($result)){
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
        switch ($type){
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