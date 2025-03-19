<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectoralFile extends Model
{
    protected $fillable = [
        'filename',
        'checksum',
        'status',
        'uploaded_by',
        'ip_address',
        'error_message'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function tempVoters()
    {
        return $this->hasMany(TempVoter::class);
    }

    public function validationErrors()
    {
        return $this->hasMany(VoterValidationError::class);
    }
}
