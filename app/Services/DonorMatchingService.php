<?php

namespace App\Services;

use App\Models\DonorProfile;
use App\Models\BloodRequest;
use Illuminate\Database\Eloquent\Collection;

class DonorMatchingService
{
  public function match(BloodRequest $bloodRequest): Collection
  {
    return DonorProfile::query()
      ->where('blood_group', $bloodRequest->blood_group)
      ->where('district', $bloodRequest->district)
      ->where('is_available', true)
      ->where('approved_by_admin', true)
      ->whereHas('user', function ($query) {
        $query->where('is_verified', true);
      })
      ->with('user')
      ->get();
  }
}
