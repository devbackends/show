<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Devvly\Subscription\Models\PaymentMethod::class, function (Faker $faker, array $attributes) {
  $now = date("Y-m-d H:i:s");
  $exp_date = new \Carbon\Carbon();
  $exp_date = $exp_date->addDays($faker->randomNumber(3))->format('Y-m-d H:i:s');
  return [
    'card_token' => '',
    'jwt_token' => bcrypt($faker->words(4,true)),
    'card_type' => $faker->randomElement(['VISA','MASTERCARD','EXPRESS']),
    'last_four' => $faker->randomNumber(4),
    'exp_date' => $exp_date,
    'is_default' => $faker->boolean? 1: 0,
    'company_id' => function () {
      $company = factory(\Devvly\Subscription\Models\Company::class)->create();
      return $company->id;
    },
    'created_at' => $now,
    'updated_at' => $now
  ];
});
