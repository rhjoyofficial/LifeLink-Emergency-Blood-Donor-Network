<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\User;

class DonorResponseService
{
  public function respond(BloodRequest $bloodRequest, User $donor): DonorResponse
  {
    if ($bloodRequest->status !== 'approved') {
      throw new \Exception('Cannot respond to inactive blood request.');
    }

    if ($donor->role !== 'donor') {
      throw new \Exception('Only donors can respond to blood requests.');
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

  public function updateStatus(
    DonorResponse $response,
    string $status
  ): DonorResponse {
    $response->update([
      'response_status' => $status,
    ]);

    return $response;
  }
}
