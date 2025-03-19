<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EligibleVoter extends Model
{
    protected $table = 'eligible_voters';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'card_number',
        'is_registered'
    ];

    protected $casts = [
        'is_registered' => 'boolean'
    ];
}
