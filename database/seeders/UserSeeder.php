<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'tenant_id' => null,
            ],
            [
                'name' => 'Tenant User',
                'email' => 'tenant@test.com',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'tenant_id' => 1,
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@test.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'tenant_id' => null,
            ],
        ]);
    }
}