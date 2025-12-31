<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'district',
        'upazila',
        'blood_group',
    ];

    protected $casts = [
        'blood_group' => 'string',
    ];
    /* ===================== Relationships ===================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($profile) {
            if ($profile->user->role !== 'recipient') {
                throw new \Exception('Only recipients can have recipient profiles.');
            }
        });
    }
}
