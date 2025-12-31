<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    /* ===================== Relationships ===================== */

    public function donorProfile(): HasOne
    {
        return $this->hasOne(DonorProfile::class);
    }

    public function recipientProfile(): HasOne
    {
        return $this->hasOne(RecipientProfile::class);
    }

    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'recipient_id');
    }

    public function donorResponses(): HasMany
    {
        return $this->hasMany(DonorResponse::class, 'donor_id');
    }

    public function approvedBloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'approved_by_admin');
    }

    public function bloodRequestLogs(): HasMany
    {
        return $this->hasMany(BloodRequestLog::class, 'changed_by');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    /* ===================== Role Helpers ===================== */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function isRecipient(): bool
    {
        return $this->role === 'recipient';
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeDonors($query)
    {
        return $query->where('role', 'donor');
    }
}
