<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorshipPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'min_sponsorships',
        'max_sponsorships',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'min_sponsorships' => 'integer',
        'max_sponsorships' => 'integer'
    ];

    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class);
    }
}
