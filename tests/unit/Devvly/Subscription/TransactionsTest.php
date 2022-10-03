<?php


namespace Tests\Unit\Devvly\Subscription;

use Devvly\Clearent\Client;
use Devvly\Clearent\Models\SaleOptions;
use Devvly\Clearent\Models\Transaction;
use Devvly\Clearent\Resources\Transactions;


/**
 * @coversDefaultClass \Devvly\Clearent\Resources\Transactions
 */
class TransactionsTest extends BaseTest
{

  /**
   * @covers ::sale
   */
  public function testSale(){
    $client = $this->app->get(Client::class);
    $options = new SaleOptions();
    $options->setSaleType('SALE');
    $options->setAmount('100.0');
    $options->setCardType($this->cardType);
    $options->setJwtToken($this->jwtToken);
    $transaction = new Transactions($client);
    // Assert getting failed response
    $response = $transaction->sale($options);
    $type = $response->getPayloadType();
    $this->assertSame('error', $type);

    // Assert getting success response
    $options->setAmount('100.00');
    $response = $transaction->sale($options);
    $type = $response->getPayloadType();
    $this->assertSame('transaction', $type);
    $this->assertInstanceOf(Transaction::class, $response);
  }
}