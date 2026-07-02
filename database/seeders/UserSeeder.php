<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123123`'),
            'phone' => '9999999999',
            'status' => 1,
        ]);

        // Insert Test User
        User::insert([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('123123`'),
            'phone' => '8888888888',
            'status' => 1,
        ]);
    }
}
