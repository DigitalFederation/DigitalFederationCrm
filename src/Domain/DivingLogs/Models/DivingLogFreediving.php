<?php

namespace Domain\DivingLogs\Models;

use Database\Factories\DivingLogFreedivingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivingLogFreediving extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'diving_log_freediving';

    protected static function newFactory(): DivingLogFreedivingFactory
    {
        return DivingLogFreedivingFactory::new();
    }

    public function divingLog(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class);
    }
}
