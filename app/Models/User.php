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

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nin', 'voter_card_number', 'phone', 
        'region_id', 'status', 'birth_date', 'party_name', 'party_position'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class, 'candidate_id');
    }

    public function votedSponsorship()
    {
        return $this->hasMany(Sponsorship::class, 'voter_id');
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPERADMIN, self::ROLE_SUPERVISOR]);
    }

    public function isSupervisor()
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    public function isVoter()
    {
        return $this->role === self::ROLE_VOTER;
    }

    public function isCandidate()
    {
        return $this->role === self::ROLE_CANDIDATE;
    }

    public function hasAdminAccess()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_SUPERVISOR]);
    }

    public function canManageUsers()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    public function canAccessSettings()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function canAccessReports()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_SUPERVISOR]);
    }

    public function canValidateSponsorship()
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_SUPERVISOR]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
