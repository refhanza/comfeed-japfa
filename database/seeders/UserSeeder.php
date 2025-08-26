<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@comfeed-japfa.com'],
            [
                'name' => 'Super Administrator',
                'email' => 'admin@comfeed-japfa.com',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Create Manager User
        User::updateOrCreate(
            ['email' => 'manager@comfeed-japfa.com'],
            [
                'name' => 'System Manager',
                'email' => 'manager@comfeed-japfa.com',
                'password' => Hash::make('manager123'),
                'role' => User::ROLE_MANAGER,
                'email_verified_at' => now(),
            ]
        );

        // Create Staff User
        User::updateOrCreate(
            ['email' => 'staff@comfeed-japfa.com'],
            [
                'name' => 'Inventory Staff',
                'email' => 'staff@comfeed-japfa.com',
                'password' => Hash::make('staff123'),
                'role' => User::ROLE_STAFF,
                'email_verified_at' => now(),
            ]
        );

        // Create Regular User
        User::updateOrCreate(
            ['email' => 'user@comfeed-japfa.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@comfeed-japfa.com',
                'password' => Hash::make('user123'),
                'role' => User::ROLE_USER,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Default users with roles created successfully!');
        $this->command->info('📧 Admin: admin@comfeed-japfa.com / admin123');
        $this->command->info('👔 Manager: manager@comfeed-japfa.com / manager123');
        $this->command->info('👨‍💼 Staff: staff@comfeed-japfa.com / staff123');
        $this->command->info('👤 User: user@comfeed-japfa.com / user123');
    }
}