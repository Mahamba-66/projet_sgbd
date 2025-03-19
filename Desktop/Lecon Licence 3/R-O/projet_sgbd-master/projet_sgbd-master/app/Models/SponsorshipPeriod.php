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
        'is_active',
        'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Vérifie si la période est actuellement active
     */
    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->is_active && 
               $now->greaterThanOrEqualTo($this->start_date) && 
               $now->lessThanOrEqualTo($this->end_date);
    }

    /**
     * Désactive toutes les autres périodes lors de l'activation d'une nouvelle période
     */
    public static function deactivateAll()
    {
        self::query()->update(['is_active' => false]);
    }
}
