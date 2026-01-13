<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists to prevent duplicates
        $existingAdmin = User::where('email', 'admin@school.com')->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@school.com',
            'email_verified_at' => now(),
            'role' => 'Admin',
            'password' => Hash::make('12121212'),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@school.com');
        $this->command->info('Password: 12121212');
    }
}