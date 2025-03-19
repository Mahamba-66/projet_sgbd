<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedVoterCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'voter_card_number',
        'is_used'
    ];

    protected $casts = [
        'is_used' => 'boolean'
    ];

    public static function isCardNumberValid(string $cardNumber): bool
    {
        return self::where('voter_card_number', $cardNumber)
            ->where('is_used', false)
            ->exists();
    }

    public static function markAsUsed(string $cardNumber): bool
    {
        return self::where('voter_card_number', $cardNumber)
            ->update(['is_used' => true]) > 0;
    }
}
