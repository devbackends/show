<?php


namespace Tests\Unit\Devvly\Subscription;

use Tests\TestCase;
use Tests\Unit\Devvly\Subscription\Utils\UnitTestHelper;



class BaseTest extends TestCase
{
  use UnitTestHelper;
  protected $apiUrl;
  protected $publicKey;
  protected $privateKey;
  protected $type;
  protected $version;
  protected $cardNumber;
  protected $lastFour;
  protected $cardType;
  protected $expDate;
  protected $csc;
  protected $jwtToken;

  /**
   * Setup the test environment.
   *
   * @return void
   */
  protected function setUp(): void
  {
    parent::setUp();
    $this->apiUrl = env("CLEARENT_API_URL");
    $this->publicKey = env("CLEARENT_API_PUBLIC_KEY");
    $this->privateKey = env("CLEARENT_API_PRIVATE_KEY");
    $this->type = env("CLEARENT_API_SOFTWARE_TYPE");
    $this->version = env("CLEARENT_API_SOFTWARE_VERSION");
    $this->cardNumber = env("CLEARENT_API_CARD_NUMBER");
    $this->lastFour = env("CLEARENT_API_CARD_LAST_FOUR");
    $this->expDate = env("CLEARENT_API_CARD_EXP");
    $this->csc = env("CLEARENT_API_CSC");
    $this->cardType = env("CLEARENT_API_CARD_TYPE");
    $this->jwtToken = env("CLEARENT_API_JWT_TOKEN");
  }
}