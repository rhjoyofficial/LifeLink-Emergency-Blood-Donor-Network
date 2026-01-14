<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodRequest extends Model
{
    use HasFactory;
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_FULFILLED = 'fulfilled';
    public const STATUS_CANCELLED = 'cancelled';
    protected $fillable = [
        'recipient_id',
        'patient_name',
        'blood_group',
        'bags_required',
        'hospital_name',
        'district',
        'upazila',
        'contact_phone',
        'urgency_level',
        'needed_at',
    ];

    protected $casts = [
        'needed_at' => 'datetime',
        'bags_required' => 'integer',
    ];

    /* ===================== Relationships ===================== */

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BloodRequestLog::class);
    }

    public function donorResponses(): HasMany
    {
        return $this->hasMany(DonorResponse::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['fulfilled', 'cancelled']);
    }

    public function canDonorRespond(): bool
    {
        return $this->isApproved() &&
            !$this->isClosed() &&
            $this->needed_at->gt(now());
    }
}
