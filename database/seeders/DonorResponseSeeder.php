<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonorResponseSeeder extends Seeder
{
    public function run(): void
    {
        $donors = User::where('role', 'donor')->where('is_verified', true)->get();
        $requests = BloodRequest::where('status', BloodRequest::STATUS_APPROVED)->get();

        foreach ($requests as $request) {
            $donors->random(2)->each(function ($donor) use ($request) {
                DonorResponse::firstOrCreate([
                    'blood_request_id' => $request->id,
                    'donor_id' => $donor->id,
                ], [
                    'response_status' => 'interested',
                ]);
            });
        }
    }
}
