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
        // Only proceed if status is changing
        if (! $bloodRequest->isDirty('status')) {
            return;
        }

        // ===============================
        // Enforce valid status transitions
        // ===============================
        $allowed = [
            BloodRequest::STATUS_PENDING => [
                BloodRequest::STATUS_APPROVED,
                BloodRequest::STATUS_CANCELLED,
            ],
            BloodRequest::STATUS_APPROVED => [
                BloodRequest::STATUS_FULFILLED,
                BloodRequest::STATUS_CANCELLED,
            ],
            BloodRequest::STATUS_FULFILLED => [],
            BloodRequest::STATUS_CANCELLED => [],
        ];

        $old = $bloodRequest->getOriginal('status');
        $new = $bloodRequest->status;

        if (! in_array($new, $allowed[$old] ?? [], true)) {
            abort(422, "Invalid status transition: {$old} → {$new}");
        }

        // ===============================
        // Log valid status change
        // ===============================
        BloodRequestLog::create([
            'blood_request_id' => $bloodRequest->id,
            'old_status'       => $old,
            'new_status'       => $new,
            'changed_by'       => Auth::id() ?? $bloodRequest->approved_by_admin,
        ]);
    }
}
