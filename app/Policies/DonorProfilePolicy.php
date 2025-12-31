<?php

namespace App\Policies;

use App\Models\DonorProfile;
use App\Models\User;

class DonorProfilePolicy
{
  /**
   * Donor can update own profile
   */
  public function update(User $user, DonorProfile $profile): bool
  {
    return $user->isDonor() && $profile->user_id === $user->id;
  }

  /**
   * Admin can approve donor
   */
  public function approve(User $user): bool
  {
    return $user->isAdmin();
  }
}
