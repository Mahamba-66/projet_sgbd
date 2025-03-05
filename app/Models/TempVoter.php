<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempVoter extends Model
{
    protected $fillable = [
        'nin',
        'voter_card_number',
        'name',
        'polling_station',
        'electoral_file_id'
    ];

    public function electoralFile()
    {
        return $this->belongsTo(ElectoralFile::class);
    }
}
