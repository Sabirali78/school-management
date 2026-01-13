<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SetupSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Setting up School Management System...');
        
        // Step 1: Create Roles
        $this->command->info('📋 Creating roles...');
        
        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator'],
            ['name' => 'teacher', 'description' => 'School Teacher'],
            ['name' => 'student', 'description' => 'Student'],
            ['name' => 'parent', 'description' => 'Parent'],
        ];
        
        foreach ($roles as $role) {
            if (!DB::table('roles')->where('name', $role['name'])->exists()) {
                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("✅ Role '{$role['name']}' created");
            }
        }
        
        // Step 2: Create Admin User
        $this->command->info('👨‍💼 Creating admin user...');
        
        $adminEmail = 'admin@gmail.com';
        $adminExists = DB::table('users')->where('email', $adminEmail)->exists();
        
        if (!$adminExists) {
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Super Admin',
                'email' => $adminEmail,
                'password' => Hash::make('12121212'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ Admin user created');
        } else {
            $adminId = DB::table('users')->where('email', $adminEmail)->value('id');
            $this->command->info('ℹ️ Admin user already exists');
        }
        
        // Step 3: Assign Admin Role
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        
        if ($adminRole) {
            $roleAssigned = DB::table('role_user')
                ->where('user_id', $adminId)
                ->where('role_id', $adminRole->id)
                ->exists();
                
            if (!$roleAssigned) {
                DB::table('role_user')->insert([
                    'user_id' => $adminId,
                    'role_id' => $adminRole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info('✅ Admin role assigned');
            } else {
                $this->command->info('ℹ️ Admin role already assigned');
            }
        }
        
        // Step 4: Create Sample Classes
        $this->command->info('🏫 Creating sample classes...');
        
        $classes = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 
                   'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];
        
        foreach ($classes as $className) {
            if (!DB::table('classes')->where('name', $className)->exists()) {
                DB::table('classes')->insert([
                    'name' => $className,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✅ Sample classes created');
        
        // Step 5: Create System Settings
        $this->command->info('⚙️ Creating system settings...');
        
        if (!DB::table('system_settings')->where('id', 1)->exists()) {
            DB::table('system_settings')->insert([
                'school_name' => 'SmartSchoolPro Academy',
                'school_address' => '123 Education Street, City',
                'school_email' => 'info@smartschoolpro.com',
                'school_phone' => '+92 300 1234567',
                'academic_year' => date('Y') . '-' . (date('Y') + 1),
                'allow_parent_registration' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ System settings created');
        }
        
        $this->command->newLine();
        $this->command->info('🎉 SETUP COMPLETED SUCCESSFULLY!');
        $this->command->newLine();
        $this->command->info('🔑 ADMIN LOGIN DETAILS:');
        $this->command->table(
            ['Item', 'Details'],
            [
                ['Email', 'admin@smartschool.com'],
                ['Password', 'admin123'],
                ['Login URL', url('/login')],
                ['Admin Dashboard', url('/admin/dashboard')],
            ]
        );
    }
}