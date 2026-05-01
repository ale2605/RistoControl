<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::upsert([
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Owner', 'slug' => 'owner'],
        ], ['slug'], ['name']);
    }
}
