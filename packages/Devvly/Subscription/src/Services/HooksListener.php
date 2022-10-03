<?php


namespace Devvly\Subscription\Services;


use Psr\Log\LoggerInterface;

class HooksListener
{

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;


  /**
   * HooksListener constructor.
   *
   * @param  \Psr\Log\LoggerInterface  $logger
   */
  public function __construct(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  public function onPayment($data)
  {
    // @todo: handle this hook
  }

  public function onRecurringPayment($data)
  {
    // @todo: handle this hook
  }
}