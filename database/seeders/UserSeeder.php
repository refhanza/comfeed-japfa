<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@comfeed.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff Inventory',
            'username' => 'staff',
            'email' => 'staff@comfeed.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create demo user
        User::create([
            'name' => 'Demo User',
            'username' => 'demo',
            'email' => 'demo@comfeed.com',
            'password' => Hash::make('demo123'),
            'email_verified_at' => now(),
        ]);
    }
}