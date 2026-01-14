<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DonorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_group',
        'district',
        'upazila',
        'last_donation_date',
        'is_available',
        'approved_by_admin',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'approved_by_admin' => 'boolean',
        'last_donation_date' => 'date',
    ];

    /* ===================== Relationships ===================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->is_available && $this->approved_by_admin;
    }

    public function canDonate(): bool
    {
        // If they have never donated, they can donate
        if (!$this->last_donation_date) {
            return true;
        }

        // Check if 90 days have passed since the last donation
        return Carbon::parse($this->last_donation_date)->addDays(90)->isPast();
    }
}
