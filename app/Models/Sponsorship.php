<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'voter_id',
        'candidate_id',
        'sponsorship_period_id',
        'status',
        'rejection_reason'
    ];

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function period()
    {
        return $this->belongsTo(SponsorshipPeriod::class, 'sponsorship_period_id');
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    public function scopeInvalid($query)
    {
        return $query->where('status', 'invalid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
