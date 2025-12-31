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
        $request = BloodRequest::first();
        $donors = User::where('role', 'donor')->take(2)->get();

        foreach ($donors as $donor) {
            DonorResponse::create([
                'blood_request_id' => $request->id,
                'donor_id' => $donor->id,
                'response_status' => 'interested',
            ]);
        }
    }
}
