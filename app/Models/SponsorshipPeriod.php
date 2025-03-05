<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorshipPeriod extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'min_sponsorships',
        'max_sponsorships',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function isOpen()
    {
        $now = now();
        return $this->status == 'active' && 
               $now->greaterThanOrEqualTo($this->start_date) && 
               $now->lessThanOrEqualTo($this->end_date);
    }
}
