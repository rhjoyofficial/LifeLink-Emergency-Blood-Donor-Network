<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RecipientProfile;
use Illuminate\Database\Seeder;

class RecipientProfileSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'recipient')->each(function ($user) {
            RecipientProfile::create([
                'user_id' => $user->id,
                'district' => 'Dhaka',
                'upazila' => 'Dhanmondi',
            ]);
        });
    }
}
