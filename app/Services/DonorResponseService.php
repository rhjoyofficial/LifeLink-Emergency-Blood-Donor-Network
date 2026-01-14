<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class DonorResponseService
{
  public function respond(BloodRequest $bloodRequest, User $donor): DonorResponse
  {
    if ($bloodRequest->status !== 'approved') {
      throw ValidationException::withMessages([
        'status' => 'Cannot respond to inactive blood request.',
      ]);
    }
    if (! $bloodRequest->canDonorRespond()) {
      throw ValidationException::withMessages([
        'status' => 'This blood request is no longer active.',
      ]);
    }
    if (!$donor->donorProfile || !$donor->donorProfile->isActive()) {
      throw ValidationException::withMessages([
        'profile' => 'Your donor profile is not active or approved.',
      ]);
    }
    if (!$donor->donorProfile->canDonate()) {
      throw ValidationException::withMessages([
        'eligibility' => 'You are not eligible to donate yet. Minimum 90 days required between donations.',
      ]);
    }
    return DonorResponse::firstOrCreate(
      [
        'blood_request_id' => $bloodRequest->id,
        'donor_id' => $donor->id,
      ],
      [
        'response_status' => 'interested',
      ]
    );
  }

  public function updateStatus(DonorResponse $response, string $status): DonorResponse
  {
    $response->update([
      'response_status' => $status,
    ]);

    return $response;
  }
}
