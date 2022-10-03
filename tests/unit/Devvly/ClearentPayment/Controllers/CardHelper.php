<?php


namespace Tests\unit\Devvly\ClearentPayment\Controllers;


use Devvly\ClearentPayment\Repositories\ClearentCardRepository;
use Devvly\ClearentPayment\Repositories\ClearentCartRepository;

trait CardHelper
{
  protected function cleanUp()
  {
    $cardRepo = $this->app->get(ClearentCardRepository::class);
    /** @var \Devvly\ClearentPayment\Models\ClearentCard[] $cards */
    $cards = $cardRepo->all();
    foreach ($cards as $card) {
      $card->forceDelete();
    }

    $cartRepo = $this->app->get(ClearentCartRepository::class);
    /** @var \Devvly\ClearentPayment\Models\ClearentCart[] $carts */
    $carts = $cartRepo->all();
    foreach ($carts as $cart) {
      $cart->forceDelete();
    }
  }
  protected function createCard()
  {
    $this->cleanUp();
    $url = route('clearent.account.store.card');
    $data = [
      'jwt_token' => $this->jwtToken,
      'card_type' => $this->cardType,
      'last_four' => $this->lastFour,
    ];
    $response = $this
      ->postJson($url, $data);
    $content = json_decode($response->getContent(), true);
    $this->assertIsArray($content);
    $response->assertStatus(200);
    return $content;
  }

  protected function createCart()
  {
    $content = $this->createCard();

    $url = route('clearent.cart.create');
    $data = [
      'savedCardSelectedId' => $content['id'],
    ];
    $response = $this
      ->postJson($url, $data);
    $content = json_decode($response->getContent(), true);
    $this->assertIsArray($content);
    $this->arrayHasKey('success', $content);
    $this->assertTrue($content['success']);
    $this->arrayHasKey($content['result']);
    $this->assertIsArray($content['result']);
    $response->assertStatus(200);
    return $content;
  }
}