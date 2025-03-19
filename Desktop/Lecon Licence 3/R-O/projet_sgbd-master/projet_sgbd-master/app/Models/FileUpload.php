<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = [
        'file_path',
        'status',
        'error_message'
    ];

    public function validationErrors()
    {
        return $this->hasMany(VoterValidationError::class, 'upload_attempt_id');
    }
}
