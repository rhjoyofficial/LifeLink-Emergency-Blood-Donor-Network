<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    public function run(): void
    {
        $recipients = User::where('role', 'recipient')->get();
        $admin = User::where('role', 'admin')->first();

        foreach ($recipients as $recipient) {

            // Pending
            BloodRequest::create([
                'recipient_id' => $recipient->id,
                'patient_name' => 'Patient Pending',
                'blood_group' => 'O+',
                'bags_required' => 2,
                'hospital_name' => 'Dhaka Medical',
                'district' => 'Dhaka',
                'upazila' => 'Dhanmondi',
                'contact_phone' => '01700000000',
                'urgency_level' => 'low',
                'needed_at' => Carbon::now()->addDays(3),
                'status' => BloodRequest::STATUS_PENDING,
            ]);

            // Approved
            BloodRequest::create([
                'recipient_id' => $recipient->id,
                'patient_name' => 'Patient Approved',
                'blood_group' => 'A+',
                'bags_required' => 1,
                'hospital_name' => 'Square Hospital',
                'district' => 'Dhaka',
                'upazila' => 'Panthapath',
                'contact_phone' => '01800000000',
                'urgency_level' => 'critical',
                'needed_at' => Carbon::now()->addDays(1),
                'status' => BloodRequest::STATUS_APPROVED,
                'approved_by_admin' => $admin->id,
            ]);
        }
    }
}
