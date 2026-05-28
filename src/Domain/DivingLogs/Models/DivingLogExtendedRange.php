<?php

namespace Domain\DivingLogs\Models;

use Database\Factories\DivingLogExtendedRangeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivingLogExtendedRange extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'diving_log_extended_range';

    protected static function newFactory(): DivingLogExtendedRangeFactory
    {
        return DivingLogExtendedRangeFactory::new();
    }

    public function divingLog(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class);
    }
}
