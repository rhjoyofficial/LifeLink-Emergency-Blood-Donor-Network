<?php

namespace App\Policies;

use App\Models\BloodRequest;
use App\Models\User;

class BloodRequestPolicy
{
  /**
   * Recipient can create blood request
   */
  public function create(User $user): bool
  {
    return $user->isRecipient() && $user->is_verified;
  }

  /**
   * Recipient can view own request, admin can view all
   */
  public function view(User $user, BloodRequest $request): bool
  {
    return $user->isAdmin() || $request->recipient_id === $user->id;
  }

  /**
   * Admin can approve pending requests
   */
  public function approve(User $user, BloodRequest $request): bool
  {
    return $user->isAdmin() && $request->status === 'pending';
  }

  /**
   * Admin can cancel non-fulfilled requests
   */
  public function cancel(User $user, BloodRequest $request): bool
  {
    return $user->isAdmin() && $request->status !== 'fulfilled';
  }

  /**
   * Admin can mark approved request as fulfilled
   */
  public function fulfill(User $user, BloodRequest $request): bool
  {
    return $user->isAdmin() && $request->status === 'approved';
  }
}
