<?php
// Ajouté le 03/19/2025 04:27:31
// feat: Relations et validations du modèle Region

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'population',
        'registered_voters',
        'required_sponsorships'
    ];

    protected $casts = [
        'population' => 'integer',
        'registered_voters' => 'integer',
        'required_sponsorships' => 'integer'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function voters(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_VOTER);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_CANDIDATE);
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class);
    }

    public function getValidSponsorshipsCount(): int
    {
        return $this->sponsorships()
            ->where('status', 'validated')
            ->count();
    }

    public function hasMetSponsorshipRequirement(): bool
    {
        return $this->getValidSponsorshipsCount() >= $this->required_sponsorships;
    }

    public function calculateRequiredSponsorships(): int
    {
        // Par exemple : 1% des électeurs inscrits
        $required = ceil($this->registered_voters * 0.01);
        
        // Avec un minimum de 2000 et un maximum de 5000
        return max(2000, min(5000, $required));
    }

    public function updateRequiredSponsorships(): void
    {
        $this->required_sponsorships = $this->calculateRequiredSponsorships();
        $this->save();
    }

    public function getSponsorshipProgress(): float
    {
        if ($this->required_sponsorships === 0) {
            return 0;
        }
        return ($this->getValidSponsorshipsCount() / $this->required_sponsorships) * 100;
    }
}
