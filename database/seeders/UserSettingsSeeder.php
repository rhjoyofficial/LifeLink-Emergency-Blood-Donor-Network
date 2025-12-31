<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Database\Seeder;

class UserSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            UserSettings::create([
                'user_id' => $user->id,
                'notification_email' => true,
                'notification_sms' => false,
                'notification_blood_requests' => true,
                'notification_responses' => true,
                'privacy_show_profile' => true,
                'privacy_show_contact' => true,
            ]);
        }
    }
}
