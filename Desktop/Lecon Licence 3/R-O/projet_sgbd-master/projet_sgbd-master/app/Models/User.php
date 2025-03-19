<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_VOTER = 'voter';
    const ROLE_CANDIDATE = 'candidate';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'region_id',
        'nin',
        'voter_card_number',
        'phone',
        'verification_code',
        'email_verified_at',
        'birth_date',
        'party_name',
        'party_position'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'password' => 'hashed',
        'blocked_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    /**
     * Get the activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the region that the user belongs to
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the sponsorships for the user.
     */
    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'voter_id');
    }

    /**
     * Get the received sponsorships for the user.
     */
    public function receivedSponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'candidate_id');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is a candidate
     */
    public function isCandidate(): bool
    {
        return $this->role === self::ROLE_CANDIDATE;
    }

    /**
     * Check if user is a voter
     */
    public function isVoter(): bool
    {
        return $this->role === self::ROLE_VOTER;
    }

    /**
     * Check if user is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is blocked
     */
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * Récupère le profil du candidat
     */
    public function candidateProfile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    /**
     * Récupère les parrainages donnés par l'électeur
     */
    public function givenSponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'voter_id');
    }
}
