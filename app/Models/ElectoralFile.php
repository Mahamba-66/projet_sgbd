<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectoralFile extends Model
{
    use HasFactory;

    protected $table = 'electoral_files';

    protected $fillable = [
        'filename',
        'total_records',
        'processed_records',
        'status',
        'error_message'
    ];

    protected $casts = [
        'total_records' => 'integer',
        'processed_records' => 'integer',
    ]

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function getProgressPercentage()
    {
        if ($this->total_records > 0) {
            return round(($this->processed_records / $this->total_records) * 100);
        }
        return 0;
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
