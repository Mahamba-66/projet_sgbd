<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'nin',
        'voter_card_number',
        'phone',
        'region_id',
        'status',
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the region that the user belongs to
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the sponsorships for the user.
     */
    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class, 'candidate_id');
    }

    /**
     * Get the voted sponsorships for the user.
     */
    public function votedSponsorship()
    {
        return $this->hasMany(Sponsorship::class, 'voter_id');
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPERADMIN, self::ROLE_SUPERVISOR]);
    }

    /**
     * Check if user is a supervisor
     */
    public function isSupervisor()
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    /**
     * Check if user is a voter
     */
    public function isVoter()
    {
        return $this->role === self::ROLE_VOTER;
    }

    /**
     * Check if user is a candidate
     */
    public function isCandidate()
    {
        return $this->role === self::ROLE_CANDIDATE;
    }

    /**
     * Check if user has admin access
     */
    public function hasAdminAccess()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_SUPERVISOR]);
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    /**
     * Check if user can access settings
     */
    public function canAccessSettings()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    /**
     * Check if user can access reports
     */
    public function canAccessReports()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_SUPERVISOR]);
    }

    /**
     * Check if user can validate sponsorship
     */
    public function canValidateSponsorship()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_SUPERVISOR]);
    }

    /**
     * Get the activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
