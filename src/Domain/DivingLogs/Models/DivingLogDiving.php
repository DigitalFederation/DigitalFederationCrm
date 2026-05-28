<?php

namespace Domain\DivingLogs\Models;

use Database\Factories\DivingLogDivingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivingLogDiving extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'diving_log_diving';

    protected static function newFactory(): DivingLogDivingFactory
    {
        return DivingLogDivingFactory::new();
    }

    public function divingLog(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class);
    }

    public function getSpecialityDiveAttribute()
    {
        return json_decode($this->attributes['speciality_dive']);
    }
}
