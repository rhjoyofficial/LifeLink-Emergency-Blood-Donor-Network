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
    ];

    /* ===================== Relationships ===================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
