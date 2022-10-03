<?php


namespace Tests\Unit\Devvly\Subscription;

use Devvly\Clearent\Client;
use Devvly\Clearent\Models\Customer;
use Devvly\Clearent\Models\CustomerOptions;
use Devvly\Clearent\Models\CustomerToken;
use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Models\Response;
use Devvly\Clearent\Resources\Customers;
use Devvly\Clearent\Resources\Tokens;


/**
 * @coversDefaultClass \Devvly\Clearent\Resources\Customers
 */
class CustomersTest extends BaseTest
{

  /**
   * @covers ::get
   */
  public function testGet(){
    $service = $service = new Customers($this->app->get(Client::class));
    $customer = $this->createCustomer();
    $customer = $service->get($customer->getCustomerKey());
    $this->assertInstanceOf(Customer::class, $customer, 'response should be customer');
    $this->deleteCustomer($customer->getCustomerKey());
  }

  /**
   * @covers ::all
   */
  public function testAll(){
    $service = $service = new Customers($this->app->get(Client::class));
    $customer = $this->createCustomer();
    $customers = $service->all();
    $this->assertIsArray($customers);
    $this->assertInstanceOf(Customer::class, $customers[0],'Should be Customer');
    $this->deleteCustomer($customer->getCustomerKey());
  }

  /**
   * @covers ::create
   */
  public function testCreate(){
    $customer = $this->createCustomer();
    $this->deleteCustomer($customer->getCustomerKey());
  }

  /**
   * @covers ::delete
   */
  public function testDelete(){
    $service = $service = new Customers($this->app->get(Client::class));
    // create customer:
    $customer = $this->createCustomer();
    $customer = $service->delete($customer->getCustomerKey());
    $this->assertInstanceOf(Customer::class, $customer, 'response should be customer');
    $response = $service->get($customer->getCustomerKey());
    $this->assertInstanceOf(Error::class, $response, "Should be Response");
    $this->assertSame('Customer not found: ' . $customer->getCustomerKey(), $response->getErrorMessage());
  }

  /**
   * @covers ::addTokenToCustomer
   */
  public function testAddTokenToCustomer(){
    $tokenSer = $this->app->get(Tokens::class);
    $service = new Customers($this->app->get(Client::class));
    // create customer, token and assign token to customer:
    $customer = $this->createCustomer();
    $token = $tokenSer->create($this->jwtToken);
    $response = $service->addTokenToCustomer($token, $customer->getCustomerKey());
    $this->assertInstanceOf(Response::class,$response,'Should be response');
    $this->assertSame('success', $response->getStatus(),'Status should be success');
    $payload = $response->getPayload();
    $this->assertInstanceOf(CustomerToken::class, $payload,'Should be instance of CustomerToken');
    $this->deleteCustomer($customer->getCustomerKey());
    $tokenSer->delete($token->getTokenId());
  }

  protected function createCustomer():Customer
  {
    $service = $service = new Customers($this->app->get(Client::class));
    // create customer:
    $options = new CustomerOptions("John", "Doe");
    $response = $service->create($options);
    $this->assertInstanceOf(Customer::class, $response,'Should be instance of Customer');
    return $response;
  }
  protected function deleteCustomer(string $id)
  {
    $service = new Customers($this->app->get(Client::class));
    $customer = $service->delete($id);
    $this->assertInstanceOf(Customer::class, $customer, 'response should be customer');
    // insure that it doesn't exist on clearent side:
    $response = $service->get($id);
    $this->assertInstanceOf(Error::class, $response, "Should be Response");
    $this->assertSame('Customer not found: ' . $id, $response->getErrorMessage());
  }
}