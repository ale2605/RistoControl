<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoRestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::firstOrCreate(
            ['slug' => 'demo-ristocontrol'],
            ['name' => 'RistoControl Demo', 'email' => 'demo@ristocontrol.test']
        );

        $role = Role::where('slug', 'super-admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@ristocontrol.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'restaurant_id' => $restaurant->id,
                'role_id' => $role?->id,
            ]
        );
    }
}
