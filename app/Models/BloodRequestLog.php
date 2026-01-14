<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequestLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'blood_request_id',
        'old_status',
        'new_status',
        'changed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /* ===================== Relationships ===================== */

    public function bloodRequest(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
