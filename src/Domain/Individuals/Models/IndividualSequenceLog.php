<?php

namespace Domain\Individuals\Models;

use Domain\DivingLogs\Models\DivingLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualSequenceLog extends Model
{
    use HasFactory;

    protected $fillable = ['individual_id', 'log_number'];

    public $timestamps = false;

    public function individual(): BelongsTo
    {
        return $this->belongsTo(DivingLog::class);
    }
}
