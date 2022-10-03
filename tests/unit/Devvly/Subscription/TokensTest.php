<?php


namespace Tests\Unit\Devvly\Subscription;

use Devvly\Clearent\Client;
use Devvly\Clearent\Models\Error;
use Devvly\Clearent\Models\Token;
use Devvly\Clearent\Resources\Tokens;


/**
 * @coversDefaultClass \Devvly\Clearent\Resources\Tokens
 */
class TokensTest extends BaseTest
{

  /**
   * @covers ::get
   */
  public function testGet(){
    $service = new Tokens($this->app->get(Client::class));
    $token = $service->create($this->jwtToken);
    $this->assertInstanceOf(Token::class, $token,'should be token');
    $token = $service->get($token->getTokenId());
    $this->assertInstanceOf(Token::class, $token,'should be token');
    $service->delete($token->getTokenId());
  }

  /**
   * @covers ::all
   */
  public function testAll(){
    $service = new Tokens($this->app->get(Client::class));
    $tokens = $service->all();
    $this->assertIsArray($tokens);
    $token = $tokens[0];
    $this->assertInstanceOf(Token::class, $token,'should be token');
  }

  /**
   * @covers ::delete
   */
  public function testDelete(){
    $service = new Tokens($this->app->get(Client::class));
    $token = $service->create($this->jwtToken);
    $this->assertInstanceOf(Token::class, $token,'should be token');
    $token = $service->delete($token->getTokenId());
    $this->assertInstanceOf(Token::class, $token,'should be token');
    $service->delete($token->getTokenId());
    $token = $service->get($token->getTokenId());
    $this->assertInstanceOf(Error::class, $token);
  }

  /**
   * @covers ::create
   */
  public function testCreate(){
    $client = $this->app->get(Client::class);
    $service = new Tokens($client);
    // create customer, token and assign token to customer:
    $response = $service->create($this->jwtToken);
    $this->assertSame('token', $response->getPayloadType(),'response type field should be token');
    $this->assertInstanceOf(Token::class, $response,'Should be instance of Token');
    $service->delete($response->getTokenId());
  }
}