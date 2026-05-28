<?php

namespace Domain\DivingLogs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivingLogRebreatherCcr extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'diving_log_rebreather_ccr';

    public function divingLog(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class);
    }
}
