<?php

namespace App\Policies;

use App\Models\BloodRequest;
use App\Models\User;

class DonorResponsePolicy
{
  /**
   * Only donors can respond to approved requests
   */
  public function respond(User $user, BloodRequest $request): bool
  {
    return
      $user->isDonor()
      && $user->is_verified
      && $request->status === 'approved';
  }
}
