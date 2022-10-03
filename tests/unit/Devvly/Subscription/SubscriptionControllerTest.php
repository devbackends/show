<?php


namespace Tests\Unit\Devvly\Subscription;

use Devvly\Subscription\Models\Plan;

/**
 * @coversDefaultClass \Devvly\Subscription\Http\Controllers\SubscriptionController
 */
class SubscriptionControllerTest extends BaseTest
{

  /**
   * @covers ::create
   */
  public function testCreate(){
    $domain = "http://test.localhost";
    $_SERVER['SERVER_NAME'] = $domain;
    $response = $this->get(route('subscription.create'));
    $response->assertStatus(200);
    $stop = null;
  }
  /**
   * @covers ::store
   */
  public function testStore(){
    $domain = "http://test.localhost";
    $_SERVER['SERVER_NAME'] = $domain;
    $plan = Plan::all()->first();
    $data = [
        'plan' => $plan->id,
        'jwt_token' => $this->jwtToken,
        'card_type' => $this->cardType,
        'last_four' => $this->lastFour
    ];
    $result = $this->post(route('subscription.store'), $data);
    $result->assertStatus(302);
    $stop = null;
  }

  /**
   * @covers ::hooks
   */
  public function testHooks(){
    $domain = "http://test.localhost";
    $_SERVER['SERVER_NAME'] = $domain;
    $json_path = __DIR__  . '/assets/transaction_hook.json';
    $data = file_get_contents($json_path);
    $data = json_decode($data, true);
    $url = $domain . route('subscription.hooks',[], false);
    $headers = ['Content-Type' => 'application/json'];
    $response = $this->postJson($url, $data, $headers);
    $response->assertStatus(200);
    $stop = null;
  }
}