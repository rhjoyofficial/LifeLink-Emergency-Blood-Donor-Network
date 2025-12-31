<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'response_status',
    ];

    /* ===================== Relationships ===================== */

    public function bloodRequest(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
    protected static function booted()
    {
        static::creating(function ($response) {
            if ($response->donor->role !== 'donor') {
                throw new \Exception('Only donors can respond to blood requests.');
            }
        });
    }
}
