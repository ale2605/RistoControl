<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::upsert([
            ['name' => 'Super Admin', 'slug' => 'super_admin'],
            ['name' => 'Owner', 'slug' => 'owner'],
            ['name' => 'Manager', 'slug' => 'manager'],
            ['name' => 'Staff', 'slug' => 'staff'],
            ['name' => 'Kitchen', 'slug' => 'kitchen'],
            ['name' => 'Accountant', 'slug' => 'accountant'],
        ], ['slug'], ['name']);
    }
}
