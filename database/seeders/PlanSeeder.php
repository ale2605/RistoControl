<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::upsert([
            ['name' => 'Starter', 'slug' => 'starter', 'price_monthly' => 29.00, 'max_users' => 5, 'max_locations' => 1],
            ['name' => 'Pro', 'slug' => 'pro', 'price_monthly' => 79.00, 'max_users' => 20, 'max_locations' => 3],
            ['name' => 'Business', 'slug' => 'business', 'price_monthly' => 149.00, 'max_users' => 100, 'max_locations' => 10],
        ], ['slug'], ['name', 'price_monthly', 'max_users', 'max_locations']);
    }
}
