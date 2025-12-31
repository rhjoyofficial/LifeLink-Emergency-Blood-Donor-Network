<?php

namespace App\Observers;

use App\Models\DonorProfile;

class DonorProfileObserver
{
    /**
     * Handle the DonorProfile "saving" event.
     */
    public function saving(DonorProfile $donorProfile): void
    {
        // If admin has NOT approved, donor cannot be available
        if (! $donorProfile->approved_by_admin) {
            $donorProfile->is_available = false;
        }
    }
}
