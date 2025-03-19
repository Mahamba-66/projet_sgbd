<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterValidationError extends Model
{
    protected $fillable = [
        'upload_attempt_id',
        'cin',
        'numero_electeur',
        'error_type',
        'error_message'
    ];

    public function uploadAttempt()
    {
        return $this->belongsTo(UploadAttempt::class);
    }
}
