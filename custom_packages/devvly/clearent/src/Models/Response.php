<?php


namespace Devvly\Clearent\Models;


class Response
{

  /**
   * @var int
   */
  private $code;

  /**
   * @var string
   */
  private $status;

  /**
   * @var string
   */
  private $exchangeId;

  /**
   * @var mixed
   */
  private $payload;

  public function __construct($response)
  {
    $this->code = $response['code'];
    if (isset($response['status'])) {
      $this->status = $response['status'];
    }
    if (isset($response['exchange-id'])) {
      $this->exchangeId = $response['exchange-id'];
    }
    $this->initPayload($response);
  }

  protected function initPayload($response)
  {
    $type = $response['payload']['payloadType'];
    $payload = $response['payload'];
    $links = null;
    if (isset($response['links'])) {
      $links = $response['links'];
    }
    switch ($type) {
      case "error":
        $this->payload = new Error('error', $payload['error']);
        break;
      case "errorTransaction":
        $this->payload = new Error($type, $payload['transaction']);
        break;
      case "transaction":
        $this->payload = new Transaction(
            $type,
            $payload[$type],
            $links
        );
        break;
      case "transactionToken":
        $this->payload = new Transaction(
            $type,
            $payload['transaction'],
            $links
        );
        break;
      case "token":
        $this->payload = new Token(
            $type,
            $payload['tokenResponse'],
            $links
        );
        break;

      case "tokens":
        $tokens = [];
        foreach ($payload[$type]['token'] as $token) {
          $tokenItem = new Token($type, $token, $links);
          $tokens[] = $tokenItem;
        }
        $this->payload = $tokens;
        break;
      case "customer":
        $this->payload = new Customer($type, $payload[$type]);
        break;
      case "customers":
        $customers = [];
        foreach ($payload[$type]['customer'] as $customer) {
          $customerItem = new Customer($type, $customer, $links);
          $customers[] = $customerItem;
        }
        $this->payload = $customers;
        break;
      case "customer-token":
        $this->payload = new CustomerToken($type, $payload['customer-token']);
        break;
      case "payment-plan":
        $this->payload = new PaymentPlan(
            $type,
            $payload[$type],
            $links
        );
        break;
      case "payment-plans":
        $plans = [];
        foreach ($payload[$type]['payment-plan'] as $plan) {
          $planPayload = new PaymentPlan($type, $plan, $links);
          $plans[] = $planPayload;
        }
        $this->payload = $plans;
        break;
    }
  }

  /**
   * @return int
   */
  public function getCode(): int
  {
    return $this->code;
  }

  /**
   * @return string
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * @return string|null
   */
  public function getExchangeId()
  {
    return $this->exchangeId;
  }

  /**
   * @return Payload|Payload[]|Error
   */
  public function getPayload()
  {
    return $this->payload;
  }


}