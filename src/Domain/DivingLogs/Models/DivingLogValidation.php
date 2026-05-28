<?php

namespace Domain\DivingLogs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $status_class
 * @property mixed $state
 */
class DivingLogValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'diving_log_id',
        'validator_id',
        'validator_type',
        'status_class',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    protected $table = 'diving_log_validation';

    public function divingLog(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class, 'diving_log_id');
    }

    public function validator()
    {
        return $this->morphTo();
    }
}
