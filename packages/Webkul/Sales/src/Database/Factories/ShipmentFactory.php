<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\Shipment;

$factory->define(Shipment::class, function (Faker $faker) {
    $address = factory(OrderAddress::class)->create();

    return [
        'total_qty'           => $faker->numberBetween(1, 20),
        'order_id'            => $address->order_id,
        'order_address_id'    => $address->id
    ];
});

