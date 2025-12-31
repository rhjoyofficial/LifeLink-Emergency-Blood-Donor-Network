<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\BloodRequestLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodRequestLogSeeder extends Seeder
{
    public function run(): void
    {
        $request = BloodRequest::first();
        $admin = User::where('role', 'admin')->first();

        BloodRequestLog::create([
            'blood_request_id' => $request->id,
            'old_status' => 'pending',
            'new_status' => 'approved',
            'changed_by' => $admin->id,
        ]);
    }
}
