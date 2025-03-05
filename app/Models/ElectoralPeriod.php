<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectoralPeriod extends Model
{
    protected $table = 'parrainage_electoral_periods';
    
    protected $fillable = [
        'start_date',
        'end_date',
        'min_sponsorships',
        'max_sponsorships',
        'status',
        'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'min_sponsorships' => 'integer',
        'max_sponsorships' => 'integer'
    ];

    public function isActive()
    {
        $now = now();
        return $this->status === 'active' && $this->start_date <= $now && $this->end_date >= $now;
    }

    public function isPending()
    {
        return $this->status === 'active' && $this->start_date > now();
    }

    public function isCompleted()
    {
        return $this->status === 'completed' || ($this->status === 'active' && $this->end_date < now());
    }

    public function getStatusLabel()
    {
        if ($this->isActive()) {
            return 'En cours';
        } elseif ($this->isPending()) {
            return 'À venir';
        } elseif ($this->isCompleted()) {
            return 'Terminée';
        }
        return 'Inconnu';
    }
}
