<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorshipPeriod extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function isOpen()
    {
        $now = now();
        return $this->is_active && 
               $now->greaterThanOrEqualTo($this->start_date) && 
               $now->lessThanOrEqualTo($this->end_date);
    }
}
