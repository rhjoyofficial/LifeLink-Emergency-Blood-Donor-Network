<?php

namespace App\Observers;

use App\Models\BloodRequest;
use App\Models\BloodRequestLog;
use Illuminate\Support\Facades\Auth;

class BloodRequestObserver
{
    /**
     * Handle the BloodRequest "updating" event.
     */
    public function updating(BloodRequest $bloodRequest): void
    {
        // Only log if status is actually changing
        if ($bloodRequest->isDirty('status')) {
            BloodRequestLog::create([
                'blood_request_id' => $bloodRequest->id,
                'old_status' => $bloodRequest->getOriginal('status'),
                'new_status' => $bloodRequest->status,
                'changed_by' => Auth::id() ?? $bloodRequest->approved_by_admin,
            ]);
        }
    }
}
