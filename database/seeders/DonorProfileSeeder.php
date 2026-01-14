<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DonorProfile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DonorProfileSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'donor')->each(function ($user, $index) {
            DonorProfile::create([
                'user_id' => $user->id,
                'blood_group' => collect(['A+', 'B+', 'O+', 'AB+'])->random(),
                'district' => 'Dhaka',
                'upazila' => 'Mirpur',
                'last_donation_date' => $index % 2 === 0
                    ? Carbon::now()->subDays(120)
                    : null,
                'approved_by_admin' => $index % 3 !== 0,
                'is_available' => true, // observer will auto-fix if unapproved
            ]);
        });
    }
}
