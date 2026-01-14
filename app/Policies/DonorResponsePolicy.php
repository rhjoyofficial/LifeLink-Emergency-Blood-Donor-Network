<?php

namespace App\Policies;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\User;

class DonorResponsePolicy
{
  public function respond(User $user, BloodRequest $request): bool
  {
    return $user->isDonor()
      && $user->is_verified
      && $request->status === BloodRequest::STATUS_APPROVED;
  }

  public function update(User $user, DonorResponse $response): bool
  {
    return $user->isDonor()
      && $response->donor_id === $user->id
      && $response->bloodRequest->status !== BloodRequest::STATUS_FULFILLED
      && $response->bloodRequest->status !== BloodRequest::STATUS_CANCELLED;
  }
}
