<?php
// Ajouté le 03/19/2025 04:27:31
// feat: Logique métier pour les parrainages

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sponsorship extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_VALIDATED = 'validated';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'voter_id',
        'candidate_id',
        'region_id',
        'status',
        'validation_date',
        'rejection_reason'
    ];

    protected $casts = [
        'validation_date' => 'datetime'
    ];

    public function voter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function validate(): void
    {
        $this->update([
            'status' => self::STATUS_VALIDATED,
            'validation_date' => now()
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
    }

    public function isValidated(): bool
    {
        return $this->status === self::STATUS_VALIDATED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
