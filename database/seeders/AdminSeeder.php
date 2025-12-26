<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the specific admin email already exists to prevent duplicates
        if (!User::where('email', 'admin@safaribook.com')->exists()) {
            
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@safaribook.com',
                'phone_number' => '0700000000', // Placeholder phone
                
                // We set a default known password for initial access.
                // You will change this later via the profile or forgot password flow.
                'password' => Hash::make('password25'), 
                
                // CRITICAL: Explicitly setting the role to ADMIN
                'role' => UserRole::ADMIN,
                
                // We mark email as verified immediately so you don't get locked out
                'email_verified_at' => now(), 
            ]);
            
            $this->command->info('Admin account created: admin@safaribook.com / password');
        } else {
            $this->command->warn('Admin account already exists.');
        }
    }
}
