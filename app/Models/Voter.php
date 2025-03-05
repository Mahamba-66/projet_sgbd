<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    protected $fillable = [
        'nin',
        'voter_card_number',
        'name',
        'phone',
        'email',
        'polling_station',
        'verification_code',
        'is_verified'
    ];

    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class, 'voter_nin', 'nin');
    }
}
