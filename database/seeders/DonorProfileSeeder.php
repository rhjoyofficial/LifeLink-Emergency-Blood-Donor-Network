<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DonorProfile;
use Illuminate\Database\Seeder;

class DonorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $bloodGroups = ['A+', 'B+', 'O+', 'AB+', 'O-'];

        $donors = User::where('role', 'donor')->get();

        foreach ($donors as $index => $donor) {
            DonorProfile::create([
                'user_id' => $donor->id,
                'blood_group' => $bloodGroups[$index % count($bloodGroups)],
                'district' => 'Dhaka',
                'upazila' => 'Dhanmondi',
                'last_donation_date' => now()->subMonths(rand(2, 10)),
                'is_available' => true,
                'approved_by_admin' => true,
            ]);
        }
    }
}
