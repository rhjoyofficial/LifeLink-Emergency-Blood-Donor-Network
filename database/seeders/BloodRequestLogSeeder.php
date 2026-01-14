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
        $admin = User::where('role', 'admin')->first();

        BloodRequest::all()->each(function ($request) use ($admin) {
            BloodRequestLog::create([
                'blood_request_id' => $request->id,
                'old_status' => BloodRequest::STATUS_PENDING,
                'new_status' => $request->status,
                'changed_by' => $request->approved_by_admin ?? $admin->id, 
                'created_at' => $request->updated_at,
            ]);
        });
    }
}
