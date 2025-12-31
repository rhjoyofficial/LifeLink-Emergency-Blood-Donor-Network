<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    public function run(): void
    {
        $recipient = User::where('role', 'recipient')->first();
        $admin = User::where('role', 'admin')->first();

        BloodRequest::create([
            'recipient_id' => $recipient->id,
            'patient_name' => 'Emergency Patient',
            'blood_group' => 'O+',
            'bags_required' => 2,
            'hospital_name' => 'Dhaka Medical College Hospital',
            'district' => 'Dhaka',
            'upazila' => 'Shahbag',
            'contact_phone' => '01911111111',
            'urgency_level' => 'critical',
            'status' => 'approved',
            'needed_at' => now()->addHours(6),
            'approved_by_admin' => $admin->id,
        ]);
    }
}
