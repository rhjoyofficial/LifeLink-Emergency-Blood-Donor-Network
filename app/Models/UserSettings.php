<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'notification_email',
        'notification_sms',
        'notification_blood_requests',
        'notification_responses',
        'privacy_show_profile',
        'privacy_show_contact',
    ];

    protected $casts = [
        'notification_email' => 'boolean',
        'notification_sms' => 'boolean',
        'notification_blood_requests' => 'boolean',
        'notification_responses' => 'boolean',
        'privacy_show_profile' => 'boolean',
        'privacy_show_contact' => 'boolean',
    ];

    /**
     * Get the user that owns the settings.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
