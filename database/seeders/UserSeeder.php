<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@lifelink.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
            'phone' => '01700000000',
        ]);

        // Donors
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Donor {$i}",
                'email' => "donor{$i}@lifelink.test",
                'password' => Hash::make('password'),
                'role' => 'donor',
                'is_verified' => true,
                'phone' => '0170000000' . $i,
            ]);
        }

        // Recipients
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Recipient {$i}",
                'email' => "recipient{$i}@lifelink.test",
                'password' => Hash::make('password'),
                'role' => 'recipient',
                'is_verified' => true,
                'phone' => '0180000000' . $i,
            ]);
        }

        // Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@blood.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
        ]);

        // Recipients
        User::factory()
            ->count(5)
            ->create([
                'role' => 'recipient',
                'is_verified' => true,
            ]);

        // Donors (some verified, some pending)
        User::factory()
            ->count(8)
            ->create([
                'role' => 'donor',
                'is_verified' => true,
            ]);

        User::factory()
            ->count(2)
            ->create([
                'role' => 'donor',
                'is_verified' => false,
            ]);
    }
}
