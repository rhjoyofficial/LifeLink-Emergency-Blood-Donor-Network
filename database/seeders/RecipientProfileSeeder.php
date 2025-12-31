<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RecipientProfile;
use Illuminate\Database\Seeder;

class RecipientProfileSeeder extends Seeder
{
    public function run(): void
    {
        $recipients = User::where('role', 'recipient')->get();

        foreach ($recipients as $recipient) {
            RecipientProfile::create([
                'user_id' => $recipient->id,
                'district' => 'Dhaka',
                'upazila' => 'Mirpur',
            ]);
        }
    }
}
