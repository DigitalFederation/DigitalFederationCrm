<?php

namespace Domain\DivingLogs\Models;

use Database\Factories\DivingLogRebreatherScrFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivingLogRebreatherScr extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'diving_log_rebreather_scr';

    protected static function newFactory(): DivingLogRebreatherScrFactory
    {
        return DivingLogRebreatherScrFactory::new();
    }

    public function divingLog(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class);
    }
}
