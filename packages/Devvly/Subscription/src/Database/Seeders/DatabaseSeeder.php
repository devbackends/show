<?php

namespace Devvly\Subscription\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Category\Database\Seeders\PlanTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PlanTableSeeder::class);
    }
}